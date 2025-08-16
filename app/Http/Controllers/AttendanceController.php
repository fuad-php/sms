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

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
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
                    return response()->json([
                        'success' => false,
                        'message' => 'Access denied'
                    ], 403);
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

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $date,
                'class' => SchoolClass::find($classId),
                'subject' => $subjectId ? Subject::find($subjectId) : null,
                'attendance' => $attendanceData,
                'statistics' => [
                    'total_students' => $totalStudents,
                    'marked' => $markedAttendance,
                    'not_marked' => $totalStudents - $markedAttendance,
                    'present' => $presentCount,
                    'absent' => $absentCount,
                    'late' => $lateCount,
                    'excused' => $excusedCount,
                    'attendance_rate' => $markedAttendance > 0 ? round(($presentCount / $markedAttendance) * 100, 2) : 0,
                ]
            ]
        ]);
    }

    /**
     * Mark attendance for students
     */
    public function markAttendance(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
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
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
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
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
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

            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully',
                'data' => $markedAttendance
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark attendance',
                'error' => $e->getMessage()
            ], 500);
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
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        // Access control
        if ($user->role === 'student' && $user->student->id !== $student->id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        if ($user->role === 'parent') {
            $parent = $user->parent;
            if (!$parent->students->contains($student)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }
        }

        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
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

        return response()->json([
            'success' => true,
            'data' => [
                'student' => $student->load('user', 'class'),
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
                'subject' => $subjectId ? Subject::find($subjectId) : null,
                'attendance_records' => $attendanceRecords,
                'statistics' => [
                    'total_days' => $totalDays,
                    'present_days' => $presentDays,
                    'absent_days' => $absentDays,
                    'late_days' => $lateDays,
                    'excused_days' => $excusedDays,
                    'attendance_percentage' => $attendancePercentage,
                ],
                'monthly_trends' => $monthlyTrends->values(),
            ]
        ]);
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
            return response()->json([
                'success' => false,
                'message' => 'Class not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
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

        return response()->json([
            'success' => true,
            'data' => [
                'class' => $class,
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
                'student_reports' => $studentReports,
                'class_statistics' => [
                    'total_students' => $totalStudents,
                    'average_attendance' => round($classAttendancePercentage, 2),
                ],
            ]
        ]);
    }

    /**
     * Get attendance statistics dashboard
     */
    public function statistics(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
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

        return response()->json([
            'success' => true,
            'data' => [
                'today_stats' => $todayStats,
                'monthly_trends' => $monthlyTrends,
                'class_attendance' => $classAttendance,
                'date' => $today->format('Y-m-d'),
            ]
        ]);
    }
}