<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Attendance;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display a listing of available reports.
     */
    public function index()
    {
        // Get basic statistics for the dashboard
        $statistics = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_classes' => SchoolClass::count(),
            'total_subjects' => Subject::count(),
            'total_exams' => Exam::count(),
            'total_parents' => ParentModel::count(),
        ];

        return view('reports.index', compact('statistics'));
    }

    /**
     * Generate academic performance report
     */
    public function academicPerformance(Request $request)
    {
        $request->validate([
            'class_id' => 'nullable|exists:school_classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'exam_id' => 'nullable|exists:exams,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = ExamResult::with(['student', 'exam', 'exam.subject', 'exam.class']);

        // Apply filters
        if ($request->filled('class_id')) {
            $query->whereHas('exam', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('exam', function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->filled('date_from')) {
            $query->whereHas('exam', function ($q) use ($request) {
                $q->where('date', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('exam', function ($q) use ($request) {
                $q->where('date', '<=', $request->date_to);
            });
        }

        $results = $query->get();

        // Calculate statistics
        $statistics = [
            'total_results' => $results->count(),
            'average_marks' => $results->avg('marks_obtained'),
            'pass_rate' => $results->where('is_pass', true)->count() / max($results->count(), 1) * 100,
            'grade_distribution' => $results->groupBy('grade')->map->count(),
        ];

        // Get filter options
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $exams = Exam::with(['class', 'subject'])->get();

        return view('reports.academic-performance', compact('results', 'statistics', 'classes', 'subjects', 'exams'));
    }

    /**
     * Generate attendance report
     */
    public function attendance(Request $request)
    {
        $request->validate([
            'class_id' => 'nullable|exists:school_classes,id',
            'student_id' => 'nullable|exists:students,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = Attendance::with(['student', 'student.class']);

        // Apply filters
        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $attendances = $query->get();

        // Calculate statistics
        $statistics = [
            'total_records' => $attendances->count(),
            'present_count' => $attendances->where('status', 'present')->count(),
            'absent_count' => $attendances->where('status', 'absent')->count(),
            'late_count' => $attendances->where('status', 'late')->count(),
            'attendance_rate' => $attendances->count() > 0 ? 
                ($attendances->where('status', 'present')->count() / $attendances->count()) * 100 : 0,
        ];

        // Get filter options
        $classes = SchoolClass::all();
        $students = Student::with('class')->get();

        return view('reports.attendance', compact('attendances', 'statistics', 'classes', 'students'));
    }

    /**
     * Generate student analytics report
     */
    public function studentAnalytics(Request $request)
    {
        $request->validate([
            'class_id' => 'nullable|exists:school_classes,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = Student::with(['class', 'examResults', 'attendances']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->get();

        // Calculate analytics for each student
        $analytics = $students->map(function ($student) use ($request) {
            $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : now()->startOfMonth();
            $dateTo = $request->date_to ? Carbon::parse($request->date_to) : now()->endOfMonth();

            $recentResults = $student->examResults()
                ->whereHas('exam', function ($q) use ($dateFrom, $dateTo) {
                    $q->whereBetween('date', [$dateFrom, $dateTo]);
                })
                ->get();

            $recentAttendance = $student->attendances()
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->get();

            return [
                'student' => $student,
                'average_marks' => $recentResults->avg('marks_obtained'),
                'total_exams' => $recentResults->count(),
                'attendance_rate' => $recentAttendance->count() > 0 ? 
                    ($recentAttendance->where('status', 'present')->count() / $recentAttendance->count()) * 100 : 0,
                'total_attendance' => $recentAttendance->count(),
            ];
        });

        // Get filter options
        $classes = SchoolClass::all();

        return view('reports.student-analytics', compact('analytics', 'classes'));
    }

    /**
     * Generate teacher performance report
     */
    public function teacherPerformance(Request $request)
    {
        $request->validate([
            'teacher_id' => 'nullable|exists:teachers,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = Teacher::with(['user', 'subjects', 'classes']);

        if ($request->filled('teacher_id')) {
            $query->where('id', $request->teacher_id);
        }

        $teachers = $query->get();

        // Calculate performance metrics for each teacher
        $performance = $teachers->map(function ($teacher) use ($request) {
            $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : now()->startOfMonth();
            $dateTo = $request->date_to ? Carbon::parse($request->date_to) : now()->endOfMonth();

            // Get exams conducted by this teacher
            $exams = Exam::whereHas('subject', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })->whereBetween('date', [$dateFrom, $dateTo])->get();

            // Get results for these exams
            $results = ExamResult::whereIn('exam_id', $exams->pluck('id'))->get();

            return [
                'teacher' => $teacher,
                'total_exams' => $exams->count(),
                'total_students' => $results->unique('student_id')->count(),
                'average_marks' => $results->avg('marks_obtained'),
                'pass_rate' => $results->count() > 0 ? 
                    ($results->where('is_pass', true)->count() / $results->count()) * 100 : 0,
            ];
        });

        // Get filter options
        $subjects = Subject::all();

        return view('reports.teacher-performance', compact('performance', 'subjects'));
    }

    /**
     * Generate class performance report
     */
    public function classPerformance(Request $request)
    {
        $request->validate([
            'class_id' => 'nullable|exists:school_classes,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = SchoolClass::with(['students', 'subjects', 'exams']);

        if ($request->filled('class_id')) {
            $query->where('id', $request->class_id);
        }

        $classes = $query->get();

        // Calculate performance metrics for each class
        $classPerformance = $classes->map(function ($class) use ($request) {
            $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : now()->startOfMonth();
            $dateTo = $request->date_to ? Carbon::parse($request->date_to) : now()->endOfMonth();

            // Get exam results for this class
            $results = ExamResult::whereHas('exam', function ($q) use ($class, $dateFrom, $dateTo) {
                $q->where('class_id', $class->id)
                  ->whereBetween('date', [$dateFrom, $dateTo]);
            })->get();

            // Get attendance for this class
            $attendance = Attendance::whereHas('student', function ($q) use ($class) {
                $q->where('class_id', $class->id);
            })->whereBetween('date', [$dateFrom, $dateTo])->get();

            return [
                'class' => $class,
                'total_students' => $class->students->count(),
                'total_exams' => $results->unique('exam_id')->count(),
                'average_marks' => $results->avg('marks_obtained'),
                'pass_rate' => $results->count() > 0 ? 
                    ($results->where('is_pass', true)->count() / $results->count()) * 100 : 0,
                'attendance_rate' => $attendance->count() > 0 ? 
                    ($attendance->where('status', 'present')->count() / $attendance->count()) * 100 : 0,
            ];
        });

        return view('reports.class-performance', compact('classPerformance'));
    }

    /**
     * Export report data
     */
    public function export(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:academic,attendance,student,teacher,class',
            'format' => 'required|in:pdf,excel,csv',
        ]);

        // This would typically generate and download the report
        // For now, we'll return a success message
        return response()->json([
            'message' => 'Report export functionality will be implemented with proper export libraries.',
            'report_type' => $request->report_type,
            'format' => $request->format,
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function dashboard()
    {
        $statistics = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_classes' => SchoolClass::count(),
            'total_subjects' => Subject::count(),
            'total_exams' => Exam::count(),
            'total_parents' => ParentModel::count(),
            'active_students' => Student::where('is_active', true)->count(),
            'active_teachers' => Teacher::where('is_active', true)->count(),
            'published_exams' => Exam::where('is_published', true)->count(),
            'upcoming_exams' => Exam::where('date', '>', now())->count(),
        ];

        return response()->json($statistics);
    }
}
