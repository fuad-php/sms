<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TeacherManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Teacher Management Dashboard
     */
    public function dashboard(Request $request)
    {
        // Check if user has admin role
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Access denied. Admin role required.');
        }

        $query = Teacher::with(['user', 'subjects', 'classesAsTeacher']);

        // Filters
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('designation')) {
            $query->where('designation', $request->designation);
        }

        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $teachers = $query->paginate(20);
        
        // Statistics
        $stats = [
            'total_teachers' => Teacher::count(),
            'active_teachers' => Teacher::active()->count(),
            'permanent_teachers' => Teacher::where('contract_type', 'permanent')->count(),
            'contract_teachers' => Teacher::where('contract_type', 'contract')->count(),
            'expiring_contracts' => Teacher::expiringContracts()->count(),
            'high_performers' => Teacher::highPerformers()->count(),
            'by_department' => Teacher::selectRaw('department, COUNT(*) as count')
                                   ->groupBy('department')
                                   ->pluck('count', 'department'),
            'by_designation' => Teacher::selectRaw('designation, COUNT(*) as count')
                                     ->groupBy('designation')
                                     ->pluck('count', 'designation'),
        ];

        $departments = Teacher::distinct()->pluck('department')->filter();
        $designations = Teacher::distinct()->pluck('designation')->filter();

        return view('teachers.dashboard', compact('teachers', 'stats', 'departments', 'designations'));
    }

    /**
     * Show teacher details
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'subjects', 'classesAsTeacher', 'timetables', 'leaves']);
        
        // Performance analytics
        $performance = [
            'current_rating' => $teacher->getPerformanceRating(),
            'performance_level' => $teacher->getPerformanceLevel(),
            'workload' => $teacher->getWorkload(),
            'total_students' => $teacher->getTotalStudents(),
            'experience_years' => $teacher->getExperienceInYears(),
            'contract_status' => $teacher->getContractStatus(),
        ];

        return view('teachers.show', compact('teacher', 'performance'));
    }

    /**
     * Create new teacher
     */
    public function create()
    {
        $subjects = Subject::all();
        $classes = SchoolClass::active()->get();
        
        return view('teachers.create', compact('subjects', 'classes'));
    }

    /**
     * Store new teacher
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'employee_id' => 'required|string|unique:teachers,employee_id',
            'designation' => 'required|string',
            'department' => 'nullable|string',
            'qualification' => 'required|string',
            'specialization' => 'nullable|string',
            'salary' => 'nullable|numeric|min:0',
            'joining_date' => 'required|date',
            'experience' => 'nullable|string',
            'contract_type' => 'required|in:permanent,contract,part_time,visiting',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'teacher',
                'phone' => $request->phone,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
            ]);

            // Create teacher record
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'employee_id' => $request->employee_id,
                'designation' => $request->designation,
                'department' => $request->department,
                'qualification' => $request->qualification,
                'specialization' => $request->specialization,
                'salary' => $request->salary,
                'joining_date' => $request->joining_date,
                'experience' => $request->experience,
                'contract_type' => $request->contract_type,
                'contract_start_date' => $request->contract_start_date,
                'contract_end_date' => $request->contract_end_date,
            ]);

            // Assign subjects
            if ($request->has('subjects')) {
                $teacher->subjects()->attach($request->subjects);
            }

            DB::commit();

            return redirect()->route('teachers.dashboard')
                           ->with('success', 'Teacher created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Teacher creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create teacher: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Edit teacher
     */
    public function edit(Teacher $teacher)
    {
        $teacher->load(['user', 'subjects']);
        $subjects = Subject::all();
        $classes = SchoolClass::active()->get();
        
        return view('teachers.edit', compact('teacher', 'subjects', 'classes'));
    }

    /**
     * Update teacher
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'employee_id' => 'required|string|unique:teachers,employee_id,' . $teacher->id,
            'designation' => 'required|string',
            'department' => 'nullable|string',
            'qualification' => 'required|string',
            'specialization' => 'nullable|string',
            'salary' => 'nullable|numeric|min:0',
            'joining_date' => 'required|date',
            'experience' => 'nullable|string',
            'contract_type' => 'required|in:permanent,contract,part_time,visiting',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date',
            'performance_rating' => 'nullable|numeric|min:0|max:5',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Update user account
            $teacher->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
            ]);

            // Update teacher record
            $teacher->update([
                'employee_id' => $request->employee_id,
                'designation' => $request->designation,
                'department' => $request->department,
                'qualification' => $request->qualification,
                'specialization' => $request->specialization,
                'salary' => $request->salary,
                'joining_date' => $request->joining_date,
                'experience' => $request->experience,
                'contract_type' => $request->contract_type,
                'contract_start_date' => $request->contract_start_date,
                'contract_end_date' => $request->contract_end_date,
                'performance_rating' => $request->performance_rating,
            ]);

            // Update subjects
            if ($request->has('subjects')) {
                $teacher->subjects()->sync($request->subjects);
            }

            DB::commit();

            return redirect()->route('teachers.show', $teacher)
                           ->with('success', 'Teacher updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Teacher update failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update teacher: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Delete teacher
     */
    public function destroy(Teacher $teacher)
    {
        try {
            DB::beginTransaction();

            // Soft delete teacher
            $teacher->update(['is_active' => false]);
            
            // Optionally delete user account
            // $teacher->user->delete();

            DB::commit();

            return redirect()->route('teachers.dashboard')
                           ->with('success', 'Teacher deactivated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Teacher deletion failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete teacher: ' . $e->getMessage()]);
        }
    }

    /**
     * Teacher Performance Analytics
     */
    public function performance(Request $request)
    {
        // Check if user has admin role
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Access denied. Admin role required.');
        }

        $query = Teacher::with(['user', 'subjects']);

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $teachers = $query->get();

        $performanceData = $teachers->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'name' => $teacher->getFullNameAttribute(),
                'department' => $teacher->department,
                'designation' => $teacher->designation,
                'performance_rating' => $teacher->getPerformanceRating(),
                'performance_level' => $teacher->getPerformanceLevel(),
                'workload' => $teacher->getWorkload(),
                'total_students' => $teacher->getTotalStudents(),
                'experience_years' => $teacher->getExperienceInYears(),
                'contract_status' => $teacher->getContractStatus(),
            ];
        });

        $stats = [
            'total_teachers' => $teachers->count(),
            'average_rating' => $teachers->avg('performance_rating') ?? 0,
            'high_performers' => $teachers->where('performance_rating', '>=', 4.0)->count(),
            'needs_improvement' => $teachers->where('performance_rating', '<', 3.0)->count(),
            'by_performance_level' => $performanceData->groupBy('performance_level')->map->count(),
        ];

        $departments = Teacher::distinct()->pluck('department')->filter();

        return view('teachers.performance', compact('performanceData', 'stats', 'departments'));
    }

    /**
     * Bulk operations
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,update_department,update_designation',
            'teacher_ids' => 'required|array|min:1',
            'teacher_ids.*' => 'exists:teachers,id',
            'department' => 'nullable|string|required_if:action,update_department',
            'designation' => 'nullable|string|required_if:action,update_designation',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            DB::beginTransaction();

            $teachers = Teacher::whereIn('id', $request->teacher_ids);

            switch ($request->action) {
                case 'activate':
                    $teachers->update(['is_active' => true]);
                    $message = 'Teachers activated successfully.';
                    break;
                case 'deactivate':
                    $teachers->update(['is_active' => false]);
                    $message = 'Teachers deactivated successfully.';
                    break;
                case 'update_department':
                    $teachers->update(['department' => $request->department]);
                    $message = 'Teachers department updated successfully.';
                    break;
                case 'update_designation':
                    $teachers->update(['designation' => $request->designation]);
                    $message = 'Teachers designation updated successfully.';
                    break;
            }

            DB::commit();

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Bulk action failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Bulk action failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Export teachers data
     */
    public function export(Request $request)
    {
        $teachers = Teacher::with(['user', 'subjects'])
                          ->when($request->filled('department'), function($q) use ($request) {
                              return $q->where('department', $request->department);
                          })
                          ->get();

        // Implementation for CSV/Excel export would go here
        return response()->json(['message' => 'Export functionality will be implemented']);
    }
}
