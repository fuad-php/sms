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
            'class_id' => 'nullable|exists:classes,id',
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
                $q->where('exam_date', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('exam', function ($q) use ($request) {
                $q->where('exam_date', '<=', $request->date_to);
            });
        }

        $results = $query->paginate(15);

        // Calculate statistics using the underlying collection
        $allResults = $query->get();
        $statistics = [
            'total_results' => $results->total(),
            'average_marks' => $allResults->avg('marks_obtained'),
            'pass_rate' => $allResults->where('is_pass', true)->count() / max($allResults->count(), 1) * 100,
            'grade_distribution' => $allResults->groupBy('grade')->map->count(),
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
            'class_id' => 'nullable|exists:classes,id',
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

        $attendances = $query->paginate(15);

        // Calculate statistics using the underlying collection
        $allAttendances = $query->get();
        $statistics = [
            'total_records' => $attendances->total(),
            'present_count' => $allAttendances->where('status', 'present')->count(),
            'absent_count' => $allAttendances->where('status', 'absent')->count(),
            'late_count' => $allAttendances->where('status', 'late')->count(),
            'attendance_rate' => $allAttendances->count() > 0 ? 
                ($allAttendances->where('status', 'present')->count() / $allAttendances->count()) * 100 : 0,
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
            'class_id' => 'nullable|exists:classes,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = Student::with(['class', 'examResults', 'attendances']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->paginate(15);

        // Calculate analytics for each student using the underlying collection
        $allStudents = $query->get();
        $analytics = $allStudents->map(function ($student) use ($request) {
            $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : now()->startOfMonth();
            $dateTo = $request->date_to ? Carbon::parse($request->date_to) : now()->endOfMonth();

            $recentResults = $student->examResults()
                ->whereHas('exam', function ($q) use ($dateFrom, $dateTo) {
                    $q->whereBetween('exam_date', [$dateFrom, $dateTo]);
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
        $allStudents = Student::with('class')->get(); // Collection for filter dropdown

        return view('reports.student-analytics', compact('analytics', 'classes', 'students', 'allStudents'));
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

        $query = Teacher::with(['user', 'subjects', 'classesAsTeacher']);

        if ($request->filled('teacher_id')) {
            $query->where('id', $request->teacher_id);
        }

        $teachers = $query->paginate(15);

        // Calculate performance metrics for each teacher using the underlying collection
        $allTeachers = $query->get();
        $performance = $allTeachers->map(function ($teacher) use ($request) {
            $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : now()->startOfMonth();
            $dateTo = $request->date_to ? Carbon::parse($request->date_to) : now()->endOfMonth();

            // Get exams conducted by this teacher
            $exams = Exam::whereHas('subject', function ($q) use ($teacher) {
                $q->whereHas('classes', function ($classQuery) use ($teacher) {
                    $classQuery->where('teacher_id', $teacher->user_id);
                });
            })->whereBetween('exam_date', [$dateFrom, $dateTo])->get();

            // Get results for these exams
            $results = ExamResult::whereIn('exam_id', $exams->pluck('id'))->get();

            return [
                'teacher' => $teacher,
                'name' => $teacher->user->name,
                'email' => $teacher->user->email,
                'subjects_count' => $teacher->subjects->count(),
                'classes_count' => $teacher->classesAsTeacher->count(),
                'total_exams' => $exams->count(),
                'students_count' => $results->unique('student_id')->count(),
                'average_marks' => $results->avg('marks_obtained') ? round($results->avg('marks_obtained'), 2) : 'N/A',
                'average_performance' => $results->avg('marks_obtained') ? round($results->avg('marks_obtained'), 2) : 0,
                'pass_rate' => $results->count() > 0 ? 
                    round(($results->where('is_pass', true)->count() / $results->count()) * 100, 2) : 0,
            ];
        });

        // Get filter options
        $subjects = Subject::all();

        return view('reports.teacher-performance', compact('performance', 'subjects', 'teachers'));
    }

    /**
     * Generate class performance report
     */
    public function classPerformance(Request $request)
    {
        $request->validate([
            'class_id' => 'nullable|exists:classes,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = SchoolClass::with(['students', 'subjects', 'exams', 'classTeacher']);

        if ($request->filled('class_id')) {
            $query->where('id', $request->class_id);
        }

        $classes = $query->paginate(15);

        // Calculate performance metrics for each class using the underlying collection
        $allClasses = $query->get();
        $classPerformance = $allClasses->map(function ($class) use ($request) {
            $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : now()->startOfMonth();
            $dateTo = $request->date_to ? Carbon::parse($request->date_to) : now()->endOfMonth();

            // Get exam results for this class
            $results = ExamResult::whereHas('exam', function ($q) use ($class, $dateFrom, $dateTo) {
                $q->where('class_id', $class->id)
                  ->whereBetween('exam_date', [$dateFrom, $dateTo]);
            })->get();

            // Get attendance for this class
            $attendance = Attendance::whereHas('student', function ($q) use ($class) {
                $q->where('class_id', $class->id);
            })->whereBetween('date', [$dateFrom, $dateTo])->get();

            return [
                'name' => $class->name,
                'teacher_name' => $class->class_teacher ? $class->class_teacher->name : 'N/A',
                'section_name' => $class->section ?: 'N/A',
                'students_count' => $class->students->count(),
                'subjects_count' => $class->subjects->count(),
                'total_exams' => $results->unique('exam_id')->count(),
                'average_marks' => $results->avg('marks_obtained') ? round($results->avg('marks_obtained'), 2) : 'N/A',
                'pass_rate' => $results->count() > 0 ? 
                    round(($results->where('is_pass', true)->count() / $results->count()) * 100, 2) : 0,
                'attendance_rate' => $attendance->count() > 0 ? 
                    round(($attendance->where('status', 'present')->count() / $attendance->count()) * 100, 2) : 0,
            ];
        });

        // Calculate overall statistics
        $statistics = [
            'total_classes' => $allClasses->count(),
            'total_students' => $allClasses->sum(function ($class) {
                return $class->students->count();
            }),
            'average_performance' => $classPerformance->where('average_marks', '!=', 'N/A')->avg('average_marks') ? 
                round($classPerformance->where('average_marks', '!=', 'N/A')->avg('average_marks'), 2) : 0,
            'pass_rate' => $classPerformance->avg('pass_rate') ? 
                round($classPerformance->avg('pass_rate'), 2) : 0,
        ];

        return view('reports.class-performance', compact('classPerformance', 'classes', 'statistics'));
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
            'upcoming_exams' => Exam::where('exam_date', '>', now())->count(),
        ];

        return response()->json($statistics);
    }
}
