<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get attendance for a specific date and class
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // If no parameters provided, just show the form
        if (!$request->filled('date') || !$request->filled('class_id')) {
            return view('attendance.index');
        }

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $date = $request->date;
        $classId = $request->class_id;
        $subjectId = $request->subject_id;

        // Authorization check for teachers
        if ($user->role === 'teacher') {
            // Teachers can only view attendance they marked or for their assigned classes/subjects
            if ($subjectId) {
                $hasAccess = $user->teacher->subjects()
                    ->wherePivot('class_id', $classId)
                    ->wherePivot('subject_id', $subjectId)
                    ->exists();
                
                if (!$hasAccess) {
                    abort(403, 'Access denied');
                }
            }
        }

        // Get all students in the class
        $students = Student::with('user')
            ->where('class_id', $classId)
            ->active()
            ->orderBy('roll_number')
            ->get();

        // Get attendance records for the specific date
        $attendanceQuery = Attendance::with(['markedBy'])
            ->forDate($date)
            ->forClass($classId);

        if ($subjectId) {
            $attendanceQuery->where('subject_id', $subjectId);
        }

        $attendanceRecords = $attendanceQuery->get()->keyBy('student_id');

        // Combine students with their attendance status
        $attendanceData = $students->map(function ($student) use ($attendanceRecords) {
            $attendance = $attendanceRecords->get($student->id);
            
            return [
                'student' => $student,
                'attendance' => $attendance,
                'status' => $attendance ? $attendance->status : 'not_marked',
                'remarks' => $attendance ? $attendance->remarks : null,
                'marked_at' => $attendance ? $attendance->created_at : null,
                'marked_by' => $attendance ? $attendance->markedBy : null,
            ];
        });

        // Calculate statistics
        $totalStudents = $students->count();
        $markedAttendance = $attendanceRecords->count();
        $presentCount = $attendanceRecords->where('status', 'present')->count();
        $absentCount = $attendanceRecords->where('status', 'absent')->count();
        $lateCount = $attendanceRecords->where('status', 'late')->count();
        $excusedCount = $attendanceRecords->where('status', 'excused')->count();

        $class = SchoolClass::find($classId);
        $subject = $subjectId ? Subject::find($subjectId) : null;
        $statistics = [
            'total_students' => $totalStudents,
            'marked' => $markedAttendance,
            'not_marked' => $totalStudents - $markedAttendance,
            'present' => $presentCount,
            'absent' => $absentCount,
            'late' => $lateCount,
            'excused' => $excusedCount,
            'attendance_rate' => $markedAttendance > 0 ? round(($presentCount / $markedAttendance) * 100, 2) : 0,
        ];

        return view('attendance.index', compact('date', 'class', 'subject', 'attendanceData', 'statistics'));
    }

    /**
     * Mark attendance for students
     */
    public function markAttendance(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Access denied');
        }

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late,excused',
            'attendance.*.remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $date = $request->date;
        $classId = $request->class_id;
        $subjectId = $request->subject_id;

        // Authorization check for teachers
        if ($user->role === 'teacher' && $subjectId) {
            $hasAccess = $user->teacher->subjects()
                ->wherePivot('class_id', $classId)
                ->wherePivot('subject_id', $subjectId)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'Access denied');
            }
        }

        try {
            $markedAttendance = [];

            foreach ($request->attendance as $attendanceData) {
                $attendance = Attendance::updateOrCreate(
                    [
                        'student_id' => $attendanceData['student_id'],
                        'date' => $date,
                        'class_id' => $classId,
                        'subject_id' => $subjectId,
                    ],
                    [
                        'status' => $attendanceData['status'],
                        'remarks' => $attendanceData['remarks'] ?? null,
                        'marked_by' => $user->id,
                    ]
                );

                $markedAttendance[] = $attendance->load('student.user', 'markedBy');
            }

            return redirect()->route('attendance.index', [
                'date' => $date,
                'class_id' => $classId,
                'subject_id' => $subjectId
            ])->with('success', 'Attendance marked successfully');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to mark attendance: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Get attendance report for a student
     */
    public function studentReport(Request $request, $studentId)
    {
        $user = auth()->user();
        $student = Student::find($studentId);

        if (!$student) {
            abort(404, 'Student not found');
        }

        // Access control
        if ($user->role === 'student' && $user->student->id !== $student->id) {
            abort(403, 'Access denied');
        }

        if ($user->role === 'parent') {
            $parent = $user->parent;
            if (!$parent) {
                abort(403, 'Parent profile not found. Please contact administrator.');
            }
            if (!$parent->students->contains($student)) {
                abort(403, 'Access denied');
            }
        }

        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $startDate = $request->start_date ?? Carbon::now()->startOfMonth();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth();
        $subjectId = $request->subject_id;

        // Get attendance records
        $attendanceQuery = $student->attendances()
            ->with(['subject', 'markedBy'])
            ->forDateRange($startDate, $endDate);

        if ($subjectId) {
            $attendanceQuery->where('subject_id', $subjectId);
        }

        $attendanceRecords = $attendanceQuery->orderBy('date', 'desc')->get();

        // Calculate statistics
        $totalDays = $attendanceRecords->count();
        $presentDays = $attendanceRecords->where('status', 'present')->count();
        $absentDays = $attendanceRecords->where('status', 'absent')->count();
        $lateDays = $attendanceRecords->where('status', 'late')->count();
        $excusedDays = $attendanceRecords->where('status', 'excused')->count();

        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;

        // Group by month for trend analysis
        $monthlyTrends = $attendanceRecords->groupBy(function ($attendance) {
            return $attendance->date->format('Y-m');
        })->map(function ($monthAttendance, $month) {
            $total = $monthAttendance->count();
            $present = $monthAttendance->where('status', 'present')->count();
            
            return [
                'month' => Carbon::parse($month)->format('M Y'),
                'total' => $total,
                'present' => $present,
                'percentage' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
            ];
        });

        $summary = [
            'present_count' => $presentDays,
            'absent_count' => $absentDays,
            'late_count' => $lateDays,
            'excused_count' => $excusedDays,
            'attendance_rate' => $attendancePercentage,
        ];

        $monthlyData = $monthlyTrends->values();

        return view('attendance.student-report', compact(
            'student',
            'attendanceRecords',
            'summary',
            'monthlyData'
        ));
    }

    /**
     * Get class attendance report
     */
    public function classReport(Request $request, $classId)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        $class = SchoolClass::find($classId);

        if (!$class) {
            abort(404, 'Class not found');
        }

        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $startDate = $request->start_date ?? Carbon::now()->startOfMonth();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth();

        // Get all students in the class
        $students = Student::with('user')
            ->where('class_id', $classId)
            ->active()
            ->get();

        // Get attendance summary for each student
        $studentReports = $students->map(function ($student) use ($startDate, $endDate) {
            $attendanceRecords = $student->attendances()
                ->forDateRange($startDate, $endDate)
                ->get();

            $totalDays = $attendanceRecords->count();
            $presentDays = $attendanceRecords->where('status', 'present')->count();
            $absentDays = $attendanceRecords->where('status', 'absent')->count();

            return [
                'student' => $student,
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'percentage' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0,
            ];
        });

        // Calculate class statistics
        $totalStudents = $students->count();
        $classAttendancePercentage = $studentReports->avg('percentage');

        $totalStudents = $students->count();
        $studentAttendance = $studentReports->map(function ($report) {
            return [
                'id' => $report['student']->id,
                'user' => $report['student']->user,
                'student_id' => $report['student']->student_id,
                'present_count' => $report['present_days'],
                'absent_count' => $report['absent_days'],
                'late_count' => $report['late_days'] ?? 0,
                'attendance_rate' => $report['percentage'],
            ];
        });

        $summary = [
            'total_present' => $studentReports->sum('present_days'),
            'total_absent' => $studentReports->sum('absent_days'),
            'average_attendance' => round($classAttendancePercentage, 2),
            'total_days' => $studentReports->first()['total_days'] ?? 0,
        ];

        return view('attendance.class-report', compact(
            'class',
            'totalStudents',
            'studentAttendance',
            'summary'
        ));
    }

    /**
     * Get attendance statistics dashboard
     */
    public function statistics(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Access denied');
        }

        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        // Today's attendance
        $todayAttendance = Attendance::forDate($today);
        $todayStats = [
            'total' => $todayAttendance->count(),
            'present' => $todayAttendance->present()->count(),
            'absent' => $todayAttendance->absent()->count(),
            'late' => $todayAttendance->where('status', 'late')->count(),
            'excused' => $todayAttendance->where('status', 'excused')->count(),
        ];
        $todayStats['percentage'] = $todayStats['total'] > 0 ? 
            round(($todayStats['present'] / $todayStats['total']) * 100, 2) : 0;

        // This month's attendance trends
        $monthlyTrends = [];
        $daysInMonth = Carbon::now()->daysInMonth;
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::now()->startOfMonth()->addDays($day - 1);
            if ($date->isFuture()) break;
            
            $dayAttendance = Attendance::forDate($date);
            $total = $dayAttendance->count();
            $present = $dayAttendance->present()->count();
            
            $monthlyTrends[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('j'),
                'total' => $total,
                'present' => $present,
                'percentage' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
            ];
        }

        // Class-wise attendance for today
        $classAttendance = SchoolClass::active()
            ->withCount(['students' => function ($query) {
                $query->active();
            }])
            ->get()
            ->map(function ($class) use ($today) {
                $todayClassAttendance = Attendance::forDate($today)
                    ->forClass($class->id)
                    ->get();
                
                $present = $todayClassAttendance->where('status', 'present')->count();
                $total = $todayClassAttendance->count();
                
                return [
                    'class' => $class,
                    'total_students' => $class->students_count,
                    'marked_attendance' => $total,
                    'present' => $present,
                    'percentage' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
                ];
            });

        $statistics = [
            'total_students' => $todayStats['total'],
            'average_attendance' => $todayStats['percentage'],
            'total_days' => count($monthlyTrends),
            'absent_rate' => $todayStats['total'] > 0 ? round((($todayStats['absent'] + $todayStats['late']) / $todayStats['total']) * 100, 2) : 0,
        ];

        $classStats = $classAttendance->map(function ($class) {
            return [
                'name' => $class['class']->name,
                'total_students' => $class['total_students'],
                'average_attendance' => $class['percentage'],
                'best_day' => 'Today',
                'worst_day' => 'N/A',
            ];
        });

        $dailyTrends = $monthlyTrends;

        return view('attendance.statistics', compact(
            'statistics',
            'classStats',
            'dailyTrends'
        ));
    }
}