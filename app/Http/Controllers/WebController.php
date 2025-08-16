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

class WebController extends Controller
{
    /**
     * Show the welcome page
     */
    public function welcome()
    {
        // Get some basic statistics for the welcome page
        $stats = [
            'total_students' => Student::active()->count(),
            'total_teachers' => Teacher::active()->count(),
            'total_classes' => SchoolClass::active()->count(),
            'total_subjects' => Subject::active()->count(),
        ];

        // Get recent announcements
        $announcements = Announcement::active()
            ->byPriority()
            ->with('createdBy')
            ->limit(3)
            ->get();

        return view('welcome', compact('stats', 'announcements'));
    }

    /**
     * Show the home page for authenticated users
     */
    public function home()
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Redirect based on user role
        switch ($user->role) {
            case 'admin':
                return redirect()->route('school.dashboard');
            case 'teacher':
                return redirect()->route('school.dashboard');
            case 'student':
                return redirect()->route('school.dashboard');
            case 'parent':
                return redirect()->route('school.dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }

    /**
     * Show the about page
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Show the contact page
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Show the help page
     */
    public function help()
    {
        return view('help');
    }
}
