<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\TeacherStoreRequest;
use App\Http\Requests\TeacherUpdateRequest;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->except(['show', 'profile']);
        $this->middleware('role:admin,teacher')->only(['show', 'profile']);
    }

    /**
     * Display a listing of teachers
     */
    public function index(Request $request)
    {
        $query = Teacher::with(['user']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('employee_id', 'like', "%{$search}%")
              ->orWhere('qualification', 'like', "%{$search}%")
              ->orWhere('specialization', 'like', "%{$search}%");
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'name') {
            $query->join('users', 'teachers.user_id', '=', 'users.id')
                  ->orderBy('users.name', $sortOrder)
                  ->select('teachers.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $teachers = $query->paginate($perPage)->appends($request->query());

        // Get statistics
        $stats = [
            'total' => Teacher::count(),
            'active' => Teacher::where('is_active', true)->count(),
            'inactive' => Teacher::where('is_active', false)->count(),
        ];

        return view('teachers.index', compact('teachers', 'stats'))->with('pageTitle', __('app.teachers'));
    }

    /**
     * Show the form for creating a new teacher
     */
    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();
        
        return view('teachers.create', compact('subjects', 'classes'))->with('pageTitle', __('app.add_teacher'));
    }

    /**
     * Store a newly created teacher
     */
    public function store(TeacherStoreRequest $request)
    {
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
                'blood_group' => $request->blood_group,
                'contact_number' => $request->contact_number,
                'is_active' => $request->has('is_active'),
            ]);

            // Handle profile image upload
            $profileImagePath = null;
            if ($request->hasFile('profile_image')) {
                $profileImagePath = $request->file('profile_image')->store('profiles', 'public');
                $user->update(['profile_image' => $profileImagePath]);
            }

            // Create teacher record
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'employee_id' => $request->employee_id,
                'designation' => $request->designation,
                'qualification' => $request->qualification,
                'specialization' => $request->specialization,
                'salary' => $request->salary,
                'joining_date' => $request->joining_date,
                'experience' => $request->experience,
                'is_active' => $request->has('is_active'),
            ]);

            // Assign subjects if provided
            if ($request->has('subjects')) {
                foreach ($request->subjects as $subjectData) {
                    $teacher->subjects()->attach($subjectData['subject_id'], [
                        'class_id' => $subjectData['class_id'],
                        'periods_per_week' => $subjectData['periods_per_week'] ?? 1,
                        'is_active' => true,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create teacher: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified teacher
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'subjects.subject', 'subjects.class', 'classesAsTeacher']);
        
        return view('teachers.show', compact('teacher'))->with('pageTitle', $teacher->user->name);
    }

    /**
     * Show the form for editing the specified teacher
     */
    public function edit(Teacher $teacher)
    {
        $teacher->load(['user', 'subjects']);
        $subjects = Subject::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();
        
        return view('teachers.edit', compact('teacher', 'subjects', 'classes'))->with('pageTitle', __('app.edit_teacher') . ': ' . $teacher->user->name);
    }

    /**
     * Update the specified teacher
     */
    public function update(TeacherUpdateRequest $request, Teacher $teacher)
    {
        try {
            DB::beginTransaction();

            // Update user information
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'blood_group' => $request->blood_group,
                'contact_number' => $request->contact_number,
                'is_active' => $request->has('is_active'),
            ];

            // Update password if provided
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $teacher->user->update($userData);

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                // Delete old profile image
                if ($teacher->user->profile_image) {
                    Storage::disk('public')->delete($teacher->user->profile_image);
                }
                
                $profileImagePath = $request->file('profile_image')->store('profiles', 'public');
                $teacher->user->update(['profile_image' => $profileImagePath]);
            }

            // Update teacher information
            $teacher->update([
                'employee_id' => $request->employee_id,
                'designation' => $request->designation,
                'qualification' => $request->qualification,
                'specialization' => $request->specialization,
                'salary' => $request->salary,
                'joining_date' => $request->joining_date,
                'experience' => $request->experience,
                'is_active' => $request->has('is_active'),
            ]);

            // Update subject assignments
            if ($request->has('subjects')) {
                $teacher->subjects()->detach();
                foreach ($request->subjects as $subjectData) {
                    $teacher->subjects()->attach($subjectData['subject_id'], [
                        'class_id' => $subjectData['class_id'],
                        'periods_per_week' => $subjectData['periods_per_week'] ?? 1,
                        'is_active' => true,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update teacher: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified teacher
     */
    public function destroy(Teacher $teacher)
    {
        try {
            DB::beginTransaction();

            // Delete profile image
            if ($teacher->user->profile_image) {
                Storage::disk('public')->delete($teacher->user->profile_image);
            }

            // Delete teacher record (this will cascade to user due to foreign key)
            $teacher->delete();

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete teacher: ' . $e->getMessage());
        }
    }

    /**
     * Toggle teacher active status
     */
    public function toggleStatus(Teacher $teacher)
    {
        try {
            $teacher->update(['is_active' => !$teacher->is_active]);
            $teacher->user->update(['is_active' => !$teacher->is_active]);

            $status = $teacher->is_active ? 'activated' : 'deactivated';
            
            return response()->json([
                'success' => true,
                'message' => "Teacher {$status} successfully.",
                'is_active' => $teacher->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update teacher status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get teacher profile
     */
    public function profile()
    {
        $teacher = auth()->user()->teacher;
        if (!$teacher) {
            abort(404, 'Teacher profile not found');
        }

        return $this->show($teacher);
    }

    /**
     * Assign subjects to teacher
     */
    public function assignSubjects(Request $request, Teacher $teacher)
    {
        $request->validate([
            'subjects' => 'required|array',
            'subjects.*.subject_id' => 'required|exists:subjects,id',
            'subjects.*.class_id' => 'required|exists:classes,id',
            'subjects.*.periods_per_week' => 'required|integer|min:1|max:10',
        ]);

        try {
            DB::beginTransaction();

            // Remove existing assignments
            $teacher->subjects()->detach();

            // Add new assignments
            foreach ($request->subjects as $subjectData) {
                $teacher->subjects()->attach($subjectData['subject_id'], [
                    'class_id' => $subjectData['class_id'],
                    'periods_per_week' => $subjectData['periods_per_week'],
                    'is_active' => true,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Subjects assigned successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign subjects: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get classes associated with the teacher (as class teacher or subject teacher)
     */
    public function getClasses(Teacher $teacher)
    {
        $classTeacherOf = $teacher->classesAsTeacher()->get();

        $subjectClasses = DB::table('class_subject')
            ->join('classes', 'class_subject.class_id', '=', 'classes.id')
            ->select('classes.*')
            ->where('class_subject.teacher_id', $teacher->user_id)
            ->distinct()
            ->get();

        return response()->json([
            'class_teacher_of' => $classTeacherOf,
            'subject_classes' => $subjectClasses,
        ]);
    }

    /**
     * Get subjects assigned to the teacher with class context
     */
    public function getSubjects(Teacher $teacher)
    {
        $subjects = $teacher->subjects()->with('classes')->get()->map(function ($subject) {
            return [
                'id' => $subject->id,
                'name' => $subject->name,
                'code' => $subject->code,
                'pivot' => $subject->pivot,
            ];
        });

        return response()->json($subjects);
    }

    /**
     * Get simple performance metrics for the teacher
     */
    public function getPerformance(Teacher $teacher)
    {
        $classesCount = DB::table('class_subject')
            ->where('teacher_id', $teacher->user_id)
            ->distinct('class_id')
            ->count('class_id');

        $subjectsCount = DB::table('class_subject')
            ->where('teacher_id', $teacher->user_id)
            ->distinct('subject_id')
            ->count('subject_id');

        $attendanceMarked = \App\Models\Attendance::where('marked_by', $teacher->user_id)->count();

        return response()->json([
            'classes_taught' => $classesCount,
            'subjects_taught' => $subjectsCount,
            'attendance_marked' => $attendanceMarked,
        ]);
    }

    /**
     * Get teacher statistics
     */
    public function statistics()
    {
        $stats = [
            'total_teachers' => Teacher::count(),
            'active_teachers' => Teacher::where('is_active', true)->count(),
            'inactive_teachers' => Teacher::where('is_active', false)->count(),
            'new_teachers_this_month' => Teacher::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'teachers_by_qualification' => Teacher::selectRaw('qualification, COUNT(*) as count')
                ->groupBy('qualification')
                ->get(),
            'teachers_by_experience' => Teacher::selectRaw('
                CASE 
                    WHEN experience < 2 THEN "0-2 years"
                    WHEN experience < 5 THEN "2-5 years"
                    WHEN experience < 10 THEN "5-10 years"
                    ELSE "10+ years"
                END as experience_range,
                COUNT(*) as count
            ')
            ->groupBy('experience_range')
            ->get(),
        ];

        return response()->json($stats);
    }
}
