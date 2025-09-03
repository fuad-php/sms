<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,teacher')->except(['index', 'show']);
    }

    /**
     * Display a listing of subjects
     */
    public function index(Request $request)
    {
        $query = Subject::withCount(['classes as classes_count' => function ($query) {
            $query->where('class_subject.is_active', true);
        }]);

        // Filter by active status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $subjects = $query->paginate($perPage);

        // Get statistics
        $stats = [
            'total_subjects' => Subject::count(),
            'active_subjects' => Subject::where('is_active', true)->count(),
            'total_classes_using_subjects' => DB::table('class_subject')
                ->where('is_active', true)
                ->distinct('class_id')
                ->count(),
        ];

        return view('subjects.index', compact('subjects', 'stats'));
    }

    /**
     * Show the form for creating a new subject
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * Store a newly created subject
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:subjects,code',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $subject = Subject::create([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'description' => $request->description,
                'color' => $request->color ?: '#3B82F6', // Default blue color
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('subjects.show', $subject)
                           ->with('success', 'Subject created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create subject: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Display the specified subject
     */
    public function show(Subject $subject)
    {
        $subject->load(['classes.classTeacher', 'timetables.teacher', 'timetables.class']);
        
        // Get subject statistics
        $stats = [
            'classes_count' => $subject->getClassesCount(),
            'total_periods' => $subject->getTotalPeriodsPerWeek(),
            'teachers_count' => $subject->teachers()->distinct()->count(),
        ];

        // Get recent timetables for this subject
        $recentTimetables = $subject->timetables()
                                  ->with(['class', 'teacher'])
                                  ->orderBy('created_at', 'desc')
                                  ->limit(5)
                                  ->get();

        return view('subjects.show', compact('subject', 'stats', 'recentTimetables'));
    }

    /**
     * Show the form for editing the specified subject
     */
    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    /**
     * Update the specified subject
     */
    public function update(Request $request, Subject $subject)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $subject->update([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'description' => $request->description,
                'color' => $request->color ?: '#3B82F6',
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('subjects.show', $subject)
                           ->with('success', 'Subject updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update subject: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Remove the specified subject
     */
    public function destroy(Subject $subject)
    {
        try {
            // Check if subject is assigned to any classes
            if ($subject->classes()->count() > 0) {
                return back()->withErrors(['error' => 'Cannot delete subject that is assigned to classes.']);
            }

            $subject->delete();

            return redirect()->route('subjects.index')
                           ->with('success', 'Subject deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete subject: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle subject active status
     */
    public function toggleStatus(Subject $subject)
    {
        try {
            $subject->update(['is_active' => !$subject->is_active]);
            
            $status = $subject->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "Subject {$status} successfully.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update subject status: ' . $e->getMessage()]);
        }
    }

    /**
     * Assign subject to classes
     */
    public function assignToClasses(Request $request, Subject $subject)
    {
        $validator = Validator::make($request->all(), [
            'assignments' => 'required|array',
            'assignments.*.class_id' => 'required|exists:classes,id',
            'assignments.*.teacher_id' => 'required|exists:users,id',
            'assignments.*.periods_per_week' => 'required|integer|min:1|max:20',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            DB::beginTransaction();

            // Remove existing assignments
            $subject->classes()->detach();

            // Add new assignments
            foreach ($request->assignments as $assignment) {
                $subject->classes()->attach($assignment['class_id'], [
                    'teacher_id' => $assignment['teacher_id'],
                    'periods_per_week' => $assignment['periods_per_week'],
                    'is_active' => true,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Subject assignments updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update subject assignments: ' . $e->getMessage()]);
        }
    }

    /**
     * Get subject statistics
     */
    public function statistics()
    {
        $stats = [
            'total_subjects' => Subject::count(),
            'active_subjects' => Subject::where('is_active', true)->count(),
            'inactive_subjects' => Subject::where('is_active', false)->count(),
            'total_assignments' => DB::table('class_subject')->where('is_active', true)->count(),
        ];

        // Subject-wise statistics
        $subjectStats = Subject::withCount(['classes as classes_count' => function ($query) {
            $query->where('class_subject.is_active', true);
        }])->get()->map(function ($subject) {
            return [
                'name' => $subject->name,
                'code' => $subject->code,
                'classes_count' => $subject->classes_count,
                'is_active' => $subject->is_active,
            ];
        });

        return response()->json([
            'stats' => $stats,
            'subject_stats' => $subjectStats,
        ]);
    }

    /**
     * Get classes using this subject
     */
    public function getClasses(Subject $subject)
    {
        $classes = $subject->classes()
                          ->with(['classTeacher', 'pivot'])
                          ->wherePivot('is_active', true)
                          ->get();

        return response()->json($classes);
    }
}
