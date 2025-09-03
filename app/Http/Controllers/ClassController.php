<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolClass;
use App\Models\User;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,teacher')->except(['index', 'show']);
    }

    /**
     * Display a listing of classes
     */
    public function index(Request $request)
    {
        $query = SchoolClass::with(['classTeacher', 'students']);

        // Filter by active status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('section', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $classes = $query->paginate($perPage);

        // Get statistics
        $stats = [
            'total_classes' => SchoolClass::count(),
            'active_classes' => SchoolClass::where('is_active', true)->count(),
            'total_students' => Student::where('is_active', true)->count(),
            'total_capacity' => SchoolClass::sum('capacity'),
        ];

        return view('classes.index', compact('classes', 'stats'));
    }

    /**
     * Show the form for creating a new class
     */
    public function create()
    {
        $teachers = User::where('role', 'teacher')
                       ->where('is_active', true)
                       ->orderBy('name')
                       ->get();

        return view('classes.create', compact('teachers'));
    }

    /**
     * Store a newly created class
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'class_teacher_id' => 'nullable|exists:users,id',
            'capacity' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $class = SchoolClass::create([
                'name' => $request->name,
                'section' => $request->section,
                'description' => $request->description,
                'class_teacher_id' => $request->class_teacher_id,
                'capacity' => $request->capacity,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('classes.show', $class)
                           ->with('success', 'Class created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create class: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Display the specified class
     */
    public function show(SchoolClass $class)
    {
        $class->load(['classTeacher', 'students.user', 'subjects', 'timetables.subject', 'timetables.teacher']);
        
        // Get class statistics
        $stats = [
            'student_count' => $class->getStudentCount(),
            'available_seats' => $class->getAvailableSeats(),
            'attendance_today' => $class->getClassAttendanceToday(),
            'subject_count' => $class->subjects()->count(),
        ];

        // Get recent announcements for this class
        $recentAnnouncements = $class->announcements()
                                   ->where('is_published', true)
                                   ->orderBy('created_at', 'desc')
                                   ->limit(5)
                                   ->get();

        return view('classes.show', compact('class', 'stats', 'recentAnnouncements'));
    }

    /**
     * Show the form for editing the specified class
     */
    public function edit(SchoolClass $class)
    {
        $teachers = User::where('role', 'teacher')
                       ->where('is_active', true)
                       ->orderBy('name')
                       ->get();

        return view('classes.edit', compact('class', 'teachers'));
    }

    /**
     * Update the specified class
     */
    public function update(Request $request, SchoolClass $class)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'class_teacher_id' => 'nullable|exists:users,id',
            'capacity' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $class->update([
                'name' => $request->name,
                'section' => $request->section,
                'description' => $request->description,
                'class_teacher_id' => $request->class_teacher_id,
                'capacity' => $request->capacity,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('classes.show', $class)
                           ->with('success', 'Class updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update class: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Remove the specified class
     */
    public function destroy(SchoolClass $class)
    {
        try {
            // Check if class has students
            if ($class->students()->count() > 0) {
                return back()->withErrors(['error' => 'Cannot delete class with existing students.']);
            }

            $class->delete();

            return redirect()->route('classes.index')
                           ->with('success', 'Class deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete class: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle class active status
     */
    public function toggleStatus(SchoolClass $class)
    {
        try {
            $class->update(['is_active' => !$class->is_active]);
            
            $status = $class->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "Class {$status} successfully.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update class status: ' . $e->getMessage()]);
        }
    }

    /**
     * Assign subjects to class
     */
    public function assignSubjects(Request $request, SchoolClass $class)
    {
        $validator = Validator::make($request->all(), [
            'subjects' => 'required|array',
            'subjects.*.subject_id' => 'required|exists:subjects,id',
            'subjects.*.teacher_id' => 'required|exists:users,id',
            'subjects.*.periods_per_week' => 'required|integer|min:1|max:20',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            DB::beginTransaction();

            // Remove existing subject assignments
            $class->subjects()->detach();

            // Add new subject assignments
            foreach ($request->subjects as $subjectData) {
                $class->subjects()->attach($subjectData['subject_id'], [
                    'teacher_id' => $subjectData['teacher_id'],
                    'periods_per_week' => $subjectData['periods_per_week'],
                    'is_active' => true,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Subjects assigned successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to assign subjects: ' . $e->getMessage()]);
        }
    }

    /**
     * Get class statistics
     */
    public function statistics()
    {
        $stats = [
            'total_classes' => SchoolClass::count(),
            'active_classes' => SchoolClass::where('is_active', true)->count(),
            'inactive_classes' => SchoolClass::where('is_active', false)->count(),
            'total_capacity' => SchoolClass::sum('capacity'),
            'total_students' => Student::where('is_active', true)->count(),
            'utilization_rate' => 0,
        ];

        if ($stats['total_capacity'] > 0) {
            $stats['utilization_rate'] = round(($stats['total_students'] / $stats['total_capacity']) * 100, 2);
        }

        // Class-wise statistics
        $classStats = SchoolClass::withCount(['students' => function ($query) {
            $query->where('is_active', true);
        }])->get()->map(function ($class) {
            return [
                'name' => $class->full_name,
                'capacity' => $class->capacity,
                'students' => $class->students_count,
                'utilization' => $class->capacity > 0 ? round(($class->students_count / $class->capacity) * 100, 2) : 0,
            ];
        });

        return response()->json([
            'stats' => $stats,
            'class_stats' => $classStats,
        ]);
    }

    /**
     * Get students by class
     */
    public function getStudents(SchoolClass $class)
    {
        $students = $class->students()
                         ->with('user')
                         ->where('is_active', true)
                         ->orderBy('roll_number')
                         ->get();

        return response()->json($students);
    }
}
