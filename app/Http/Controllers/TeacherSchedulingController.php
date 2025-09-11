<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\TeacherSchedule;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TeacherSchedulingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,teacher');
    }

    /**
     * Teacher Scheduling Dashboard
     */
    public function dashboard(Request $request)
    {
        $query = TeacherSchedule::with(['teacher.user', 'class', 'subject']);

        // Filters
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        if ($request->filled('schedule_type')) {
            $query->where('schedule_type', $request->schedule_type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $schedules = $query->orderBy('day_of_week')
                          ->orderBy('start_time')
                          ->paginate(50);

        // Statistics
        $stats = [
            'total_schedules' => TeacherSchedule::count(),
            'active_schedules' => TeacherSchedule::active()->count(),
            'teachers_with_schedules' => TeacherSchedule::distinct('teacher_id')->count(),
            'classes_with_schedules' => TeacherSchedule::distinct('class_id')->count(),
            'by_day' => TeacherSchedule::selectRaw('day_of_week, COUNT(*) as count')
                                     ->groupBy('day_of_week')
                                     ->pluck('count', 'day_of_week'),
            'by_type' => TeacherSchedule::selectRaw('schedule_type, COUNT(*) as count')
                                       ->groupBy('schedule_type')
                                       ->pluck('count', 'schedule_type'),
        ];

        $teachers = Teacher::with('user')->active()->get();
        $classes = SchoolClass::active()->get();
        $subjects = Subject::all();

        return view('teacher-scheduling.dashboard', compact('schedules', 'stats', 'teachers', 'classes', 'subjects'));
    }

    /**
     * Show teacher's schedule
     */
    public function showTeacherSchedule(Teacher $teacher)
    {
        $teacher->load(['user', 'schedules.class', 'schedules.subject']);
        
        $weeklySchedule = $teacher->getWeeklySchedule();
        $workloadStats = $teacher->getWorkloadStats();
        $workloadDistribution = $teacher->getWorkloadDistribution();

        return view('teacher-scheduling.teacher-schedule', compact('teacher', 'weeklySchedule', 'workloadStats', 'workloadDistribution'));
    }

    /**
     * Create new schedule
     */
    public function create()
    {
        $teachers = Teacher::with('user')->active()->get();
        $classes = SchoolClass::active()->get();
        $subjects = Subject::all();

        return view('teacher-scheduling.create', compact('teachers', 'classes', 'subjects'));
    }

    /**
     * Store new schedule
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,user_id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'schedule_type' => 'required|in:regular,substitute,extra,remedial,exam_prep',
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after:effective_from',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check for conflicts
        $teacher = Teacher::where('user_id', $request->teacher_id)->first();
        if ($teacher->hasScheduleConflict($request->day_of_week, $request->start_time, $request->end_time)) {
            $conflicts = $teacher->getScheduleConflicts($request->day_of_week, $request->start_time, $request->end_time);
            return back()->withErrors([
                'conflict' => 'Schedule conflicts with existing classes: ' . 
                             $conflicts->pluck('class.name')->implode(', ')
            ])->withInput();
        }

        try {
            DB::beginTransaction();

            $schedule = TeacherSchedule::create([
                'teacher_id' => $request->teacher_id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'day_of_week' => $request->day_of_week,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'room_number' => $request->room_number,
                'schedule_type' => $request->schedule_type,
                'effective_from' => $request->effective_from,
                'effective_until' => $request->effective_until,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('teacher-scheduling.dashboard')
                           ->with('success', 'Schedule created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Schedule creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create schedule: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Edit schedule
     */
    public function edit(TeacherSchedule $schedule)
    {
        $schedule->load(['teacher.user', 'class', 'subject']);
        $teachers = Teacher::with('user')->active()->get();
        $classes = SchoolClass::active()->get();
        $subjects = Subject::all();

        return view('teacher-scheduling.edit', compact('schedule', 'teachers', 'classes', 'subjects'));
    }

    /**
     * Update schedule
     */
    public function update(Request $request, TeacherSchedule $schedule)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,user_id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'schedule_type' => 'required|in:regular,substitute,extra,remedial,exam_prep',
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after:effective_from',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check for conflicts (excluding current schedule)
        $teacher = Teacher::where('user_id', $request->teacher_id)->first();
        if ($teacher->hasScheduleConflict($request->day_of_week, $request->start_time, $request->end_time, $schedule->id)) {
            $conflicts = $teacher->getScheduleConflicts($request->day_of_week, $request->start_time, $request->end_time, $schedule->id);
            return back()->withErrors([
                'conflict' => 'Schedule conflicts with existing classes: ' . 
                             $conflicts->pluck('class.name')->implode(', ')
            ])->withInput();
        }

        try {
            DB::beginTransaction();

            $schedule->update([
                'teacher_id' => $request->teacher_id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'day_of_week' => $request->day_of_week,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'room_number' => $request->room_number,
                'schedule_type' => $request->schedule_type,
                'effective_from' => $request->effective_from,
                'effective_until' => $request->effective_until,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('teacher-scheduling.dashboard')
                           ->with('success', 'Schedule updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Schedule update failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update schedule: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Delete schedule
     */
    public function destroy(TeacherSchedule $schedule)
    {
        try {
            $schedule->delete();
            return redirect()->route('teacher-scheduling.dashboard')
                           ->with('success', 'Schedule deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Schedule deletion failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete schedule: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle schedule status
     */
    public function toggleStatus(TeacherSchedule $schedule)
    {
        try {
            $schedule->update(['is_active' => !$schedule->is_active]);
            
            $status = $schedule->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "Schedule {$status} successfully.");

        } catch (\Exception $e) {
            Log::error('Schedule status toggle failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to toggle schedule status: ' . $e->getMessage()]);
        }
    }

    /**
     * Get available time slots for a teacher
     */
    public function getAvailableSlots(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,user_id',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'duration' => 'nullable|integer|min:30|max:240',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid parameters'], 400);
        }

        $teacher = Teacher::where('user_id', $request->teacher_id)->first();
        $duration = $request->duration ?? 60;
        $slots = $teacher->getAvailableTimeSlots($request->day, $duration);

        return response()->json(['slots' => $slots]);
    }

    /**
     * Check for schedule conflicts
     */
    public function checkConflicts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,user_id',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'exclude_id' => 'nullable|exists:teacher_schedules,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid parameters'], 400);
        }

        $teacher = Teacher::where('user_id', $request->teacher_id)->first();
        $hasConflict = $teacher->hasScheduleConflict($request->day, $request->start_time, $request->end_time, $request->exclude_id);
        
        if ($hasConflict) {
            $conflicts = $teacher->getScheduleConflicts($request->day, $request->start_time, $request->end_time, $request->exclude_id);
            return response()->json([
                'has_conflict' => true,
                'conflicts' => $conflicts->map(function($schedule) {
                    return [
                        'id' => $schedule->id,
                        'class' => $schedule->class->name,
                        'subject' => $schedule->subject->name,
                        'time' => $schedule->getTimeRange(),
                        'room' => $schedule->room_number,
                    ];
                })
            ]);
        }

        return response()->json(['has_conflict' => false]);
    }

    /**
     * Workload analytics
     */
    public function workloadAnalytics(Request $request)
    {
        $query = Teacher::with(['user', 'schedules.class', 'schedules.subject']);

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $teachers = $query->active()->get();

        $analytics = $teachers->map(function ($teacher) {
            $workloadStats = $teacher->getWorkloadStats();
            $workloadDistribution = $teacher->getWorkloadDistribution();
            
            return [
                'teacher' => [
                    'id' => $teacher->id,
                    'name' => $teacher->getFullNameAttribute(),
                    'department' => $teacher->department,
                    'designation' => $teacher->designation,
                ],
                'workload' => $workloadStats,
                'distribution' => $workloadDistribution,
                'schedule_count' => $teacher->schedules()->active()->count(),
                'weekly_hours' => $workloadStats['weekly_total_hours'],
            ];
        });

        $stats = [
            'total_teachers' => $teachers->count(),
            'average_weekly_hours' => $analytics->avg('weekly_hours'),
            'max_weekly_hours' => $analytics->max('weekly_hours'),
            'min_weekly_hours' => $analytics->min('weekly_hours'),
            'overloaded_teachers' => $analytics->where('weekly_hours', '>', 40)->count(),
            'underloaded_teachers' => $analytics->where('weekly_hours', '<', 20)->count(),
        ];

        $departments = Teacher::distinct()->pluck('department')->filter();

        return view('teacher-scheduling.workload-analytics', compact('analytics', 'stats', 'departments'));
    }

    /**
     * Bulk operations
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete,duplicate',
            'schedule_ids' => 'required|array|min:1',
            'schedule_ids.*' => 'exists:teacher_schedules,id',
            'target_teacher_id' => 'nullable|exists:teachers,user_id|required_if:action,duplicate',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            DB::beginTransaction();

            $schedules = TeacherSchedule::whereIn('id', $request->schedule_ids);

            switch ($request->action) {
                case 'activate':
                    $schedules->update(['is_active' => true]);
                    $message = 'Schedules activated successfully.';
                    break;
                case 'deactivate':
                    $schedules->update(['is_active' => false]);
                    $message = 'Schedules deactivated successfully.';
                    break;
                case 'delete':
                    $schedules->delete();
                    $message = 'Schedules deleted successfully.';
                    break;
                case 'duplicate':
                    $originalSchedules = $schedules->get();
                    foreach ($originalSchedules as $schedule) {
                        TeacherSchedule::create([
                            'teacher_id' => $request->target_teacher_id,
                            'class_id' => $schedule->class_id,
                            'subject_id' => $schedule->subject_id,
                            'day_of_week' => $schedule->day_of_week,
                            'start_time' => $schedule->start_time,
                            'end_time' => $schedule->end_time,
                            'room_number' => $schedule->room_number,
                            'schedule_type' => $schedule->schedule_type,
                            'effective_from' => $schedule->effective_from,
                            'effective_until' => $schedule->effective_until,
                            'notes' => $schedule->notes . ' (Duplicated)',
                            'created_by' => auth()->id(),
                        ]);
                    }
                    $message = 'Schedules duplicated successfully.';
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
}
