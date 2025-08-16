<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Announcement;
use App\Models\Timetable;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get dashboard data based on user role
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        switch ($user->role) {
            case 'admin':
                return $this->getAdminDashboard();
            case 'teacher':
                return $this->getTeacherDashboard($user);
            case 'student':
                return $this->getStudentDashboard($user);
            case 'parent':
                return $this->getParentDashboard($user);
            default:
                return redirect()->route('dashboard')->with('error', 'Invalid user role');
        }
    }

    /**
     * Admin Dashboard
     */
    private function getAdminDashboard()
    {
        $totalStudents = Student::active()->count();
        $totalTeachers = Teacher::active()->count();
        $totalClasses = SchoolClass::active()->count();
        $totalSubjects = Subject::active()->count();
        
        // Today's attendance
        $today = Carbon::today();
        $todayAttendance = Attendance::forDate($today)->count();
        $todayPresent = Attendance::forDate($today)->present()->count();
        $attendanceRate = $todayAttendance > 0 ? round(($todayPresent / $todayAttendance) * 100, 2) : 0;
        
        // Recent registrations
        $recentStudents = Student::with('user', 'class')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Upcoming exams
        $upcomingExams = Exam::with('class', 'subject')
            ->upcoming()
            ->published()
            ->orderBy('exam_date')
            ->limit(5)
            ->get();
        
        // Recent announcements
        $recentAnnouncements = Announcement::active()
            ->byPriority()
            ->with('createdBy')
            ->limit(5)
            ->get();
        
        // Class-wise student count
        $classStats = SchoolClass::active()
            ->withCount('students')
            ->get()
            ->map(function ($class) {
                return [
                    'class' => $class->full_name,
                    'students' => $class->students_count,
                    'capacity' => $class->capacity,
                    'percentage' => round(($class->students_count / $class->capacity) * 100, 2)
                ];
            });

        // Monthly attendance trends (last 6 months)
        $attendanceTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyAttendance = Attendance::whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->get();
            
            $totalDays = $monthlyAttendance->count();
            $presentDays = $monthlyAttendance->where('status', 'present')->count();
            
            $attendanceTrends[] = [
                'month' => $month->format('M Y'),
                'percentage' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0
            ];
        }

        return view('dashboard.admin', compact(
            'totalStudents',
            'totalTeachers', 
            'totalClasses',
            'totalSubjects',
            'attendanceRate',
            'recentStudents',
            'upcomingExams',
            'recentAnnouncements',
            'classStats',
            'attendanceTrends'
        ));
    }

    /**
     * Teacher Dashboard
     */
    private function getTeacherDashboard(User $user)
    {
        $teacher = $user->teacher;
        
        // Today's schedule
        $todaySchedule = $teacher->getTodaySchedule();
        
        // Classes assigned as class teacher
        $classesAsTeacher = $teacher->classesAsTeacher()
            ->with('students')
            ->get();
        
        // Today's attendance to mark
        $attendanceToMark = [];
        foreach ($todaySchedule as $schedule) {
            $studentsCount = $schedule->class->getStudentCount();
            $markedCount = Attendance::forDate(Carbon::today())
                ->forClass($schedule->class_id)
                ->where('marked_by', $user->id)
                ->count();
            
            $attendanceToMark[] = [
                'schedule' => $schedule,
                'students_count' => $studentsCount,
                'marked_count' => $markedCount,
                'pending' => $studentsCount - $markedCount > 0
            ];
        }
        
        // Upcoming exams for teacher's subjects
        $upcomingExams = Exam::whereHas('subject.teachers', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->upcoming()
        ->with('class', 'subject')
        ->orderBy('exam_date')
        ->limit(5)
        ->get();
        
        // Recent announcements
        $announcements = Announcement::active()
            ->forAudience('teachers')
            ->byPriority()
            ->limit(5)
            ->get();

        return view('dashboard.teacher', compact(
            'todaySchedule',
            'classesAsTeacher',
            'attendanceToMark',
            'upcomingExams',
            'announcements'
        ));
    }

    /**
     * Student Dashboard
     */
    private function getStudentDashboard(User $user)
    {
        $student = $user->student;
        
        // Today's timetable
        $todayTimetable = $student->class->getTodayTimetable();
        
        // Current period
        $currentPeriod = $todayTimetable->first(function ($period) {
            return $period->isCurrentPeriod();
        });
        
        // Next period
        $nextPeriod = $todayTimetable->first(function ($period) {
            return $period->isUpcoming();
        });
        
        // Attendance statistics
        $attendanceStats = [
            'this_month' => $student->getAttendancePercentage(
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ),
            'last_month' => $student->getAttendancePercentage(
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ),
            'overall' => $student->getAttendancePercentage()
        ];
        
        // Recent exam results
        $recentResults = $student->examResults()
            ->with('exam.subject')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Upcoming exams
        $upcomingExams = Exam::forClass($student->class_id)
            ->upcoming()
            ->published()
            ->with('subject')
            ->orderBy('exam_date')
            ->limit(5)
            ->get();
        
        // Announcements for student
        $announcements = Announcement::active()
            ->forAudience('students')
            ->forClass($student->class_id)
            ->byPriority()
            ->limit(5)
            ->get();

        return view('dashboard.student', compact(
            'todayTimetable',
            'currentPeriod',
            'nextPeriod',
            'attendanceStats',
            'recentResults',
            'upcomingExams',
            'announcements',
            'student'
        ));
    }

    /**
     * Parent Dashboard
     */
    private function getParentDashboard(User $user)
    {
        $parent = $user->parent;
        $children = $parent->students()->with('class', 'user')->get();
        
        $childrenData = $children->map(function ($child) {
            return [
                'student' => $child,
                'attendance_percentage' => $child->getAttendancePercentage(),
                'grade_average' => $child->getCurrentGradeAverage(),
                'recent_attendance' => $child->attendances()
                    ->with('subject')
                    ->orderBy('date', 'desc')
                    ->limit(5)
                    ->get(),
                'upcoming_exams' => Exam::forClass($child->class_id)
                    ->upcoming()
                    ->published()
                    ->with('subject')
                    ->limit(3)
                    ->get(),
            ];
        });
        
        // Overall family stats
        $totalChildren = $children->count();
        $averageAttendance = $children->avg(function ($child) {
            return $child->getAttendancePercentage();
        });
        $averageGrades = $children->avg(function ($child) {
            return $child->getCurrentGradeAverage();
        });
        
        // Announcements for parents
        $announcements = Announcement::active()
            ->forAudience('parents')
            ->byPriority()
            ->limit(5)
            ->get();

        return view('dashboard.parent', compact(
            'childrenData',
            'totalChildren',
            'averageAttendance',
            'averageGrades',
            'announcements'
        ));
    }
}