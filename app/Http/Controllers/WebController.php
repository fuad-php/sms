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
use App\Models\CarouselSlide;
use App\Models\ContactInquiry;
use App\Models\Setting;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class WebController extends Controller
{
    /**
     * Show the home page
     */
    public function welcome()
    {
        // Get some basic statistics for the home page
        $stats = [
            'total_students' => Student::active()->count(),
            'total_teachers' => Teacher::active()->count(),
            'total_classes' => SchoolClass::active()->count(),
            'total_subjects' => Subject::active()->count(),
        ];

        // Get recent announcements
        $announcements = Announcement::where('target_audience', 'all')
            ->where('is_published', true)
            ->where(function($q) {
                $q->whereNull('publish_date')
                  ->orWhere('publish_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('expire_date')
                  ->orWhere('expire_date', '>=', now());
            })
            ->orderBy('priority', 'asc')
            ->orderBy('created_at', 'desc')
            ->with('createdBy')
            ->limit(4)
            ->get();

        // Get active carousel slides
        $carouselSlides = CarouselSlide::where('is_active', true)
            ->orderBy('order')
            ->get();

        // Get upcoming events
        $events = Event::published()
            ->upcoming()
            ->orderBy('start_at')
            ->limit(6)
            ->get();

        // Get school settings
        $settings = Setting::pluck('value', 'key');

        return view('home', compact('stats', 'announcements', 'carouselSlides', 'events', 'settings'));
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
            case 'staff':
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
        $settings = Setting::pluck('value', 'key');
        $teachers = Teacher::with('user')
            ->active()
            ->orderBy('joining_date')
            ->limit(12)
            ->get();
        
        return view('about', compact('settings', 'teachers'));
    }

    /**
     * Show the contact page
     */
    public function contact()
    {
        $settings = Setting::pluck('value', 'key');
        
        return view('contact', compact('settings'));
    }

    /**
     * Show the help page
     */
    public function help()
    {
        return view('help');
    }

    /**
     * Show the gallery page
     */
    public function gallery()
    {
        $carouselSlides = CarouselSlide::where('is_active', true)
            ->orderBy('order')
            ->get();
            
        return view('gallery', compact('carouselSlides'));
    }

    /**
     * Show the academic programs page
     */
    public function programs()
    {
        $classes = SchoolClass::with('subjects')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        $subjects = Subject::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('programs', compact('classes', 'subjects'));
    }

    /**
     * Show the admissions page
     */
    public function admissions()
    {
        $settings = Setting::pluck('value', 'key');
        
        return view('admissions', compact('settings'));
    }

    /**
     * Show the facilities page
     */
    public function facilities()
    {
        return view('facilities');
    }

    /**
     * Show the news page
     */
    public function news()
    {
        $announcements = Announcement::where('target_audience', 'all')
            ->where('is_published', true)
            ->where(function($q) {
                $q->whereNull('publish_date')
                  ->orWhere('publish_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('expire_date')
                  ->orWhere('expire_date', '>=', now());
            })
            ->orderBy('priority', 'asc')
            ->orderBy('created_at', 'desc')
            ->with('createdBy')
            ->paginate(10);
            
        return view('news', compact('announcements'));
    }

    /**
     * Public events landing page
     */
    public function events()
    {
        $events = Event::published()
            ->orderBy('start_at', 'desc')
            ->paginate(9);

        return view('events', compact('events'));
    }

    /**
     * Show a specific announcement
     */
    public function showAnnouncement(Announcement $announcement)
    {
        if (!$announcement->is_published || 
            ($announcement->publish_date && $announcement->publish_date > now()) ||
            ($announcement->expire_date && $announcement->expire_date < now())) {
            abort(404);
        }
        
        return view('announcements.show', compact('announcement'));
    }

    /**
     * Store contact inquiry
     */
    public function storeContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'department' => 'nullable|string|in:general,admissions,academic,administration,support',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        ContactInquiry::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'department' => $request->department ?? 'general',
            'status' => 'new',
        ]);

        return back()->with('success', __('app.contact_message_sent_successfully'));
    }

    /**
     * Get school statistics for API
     */
    public function getStats()
    {
        $stats = [
            'total_students' => Student::active()->count(),
            'total_teachers' => Teacher::active()->count(),
            'total_classes' => SchoolClass::active()->count(),
            'total_subjects' => Subject::active()->count(),
            'total_announcements' => Announcement::where('is_published', true)->count(),
            'upcoming_exams' => Exam::where('exam_date', '>', now())->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get active carousel slides for API
     */
    public function getCarouselSlides()
    {
        $slides = CarouselSlide::with('translations')
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($slide) {
                return [
                    'id' => $slide->id,
                    'title' => $slide->title_localized,
                    'subtitle' => $slide->subtitle_localized,
                    'description' => $slide->description_localized,
                    'image_url' => $slide->image_url,
                    'button_text' => $slide->button_text_localized,
                    'button_url' => $slide->button_url,
                    'order' => $slide->order,
                ];
            });

        return response()->json($slides);
    }
}
