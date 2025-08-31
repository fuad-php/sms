<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    /**
     * Display public announcements (accessible to everyone)
     */
    public function publicAnnouncements(Request $request)
    {
        $query = Announcement::with(['createdBy', 'class']);

        // Only show published and active announcements
        $query->active();

        // Only show announcements with target_audience 'all' (public announcements)
        $query->where('target_audience', 'all');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Apply priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Order by priority and creation date
        $announcements = $query->byPriority()
                              ->orderBy('created_at', 'desc')
                              ->paginate(12);

        $priorities = ['low', 'medium', 'high', 'urgent'];

        return view('announcements.public', compact('announcements', 'priorities'));
    }

    /**
     * Display a listing of announcements
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Announcement::with(['createdBy', 'class']);

        // Filter based on user role and permissions
        if ($user->role === 'admin') {
            // Admin can see all announcements
        } elseif ($user->role === 'teacher') {
            // Teachers can see announcements for their classes and general announcements
            $teacherClasses = $user->teacher->classes->pluck('id');
            $query->where(function ($q) use ($teacherClasses) {
                $q->whereNull('class_id')
                  ->orWhereIn('class_id', $teacherClasses);
            });
        } elseif ($user->role === 'student') {
            // Students can see announcements for their class and general announcements
            $studentClass = $user->student->class_id;
            $query->where(function ($q) use ($studentClass) {
                $q->whereNull('class_id')
                  ->orWhere('class_id', $studentClass);
            })->where(function ($q) {
                $q->where('target_audience', 'all')
                  ->orWhere('target_audience', 'students');
            });
        } elseif ($user->role === 'parent') {
            // Parents can see announcements for their children's classes and general announcements
            $parent = $user->parent;
            if (!$parent) {
                // If no parent record, show only general announcements
                $query->whereNull('class_id')
                      ->where(function ($q) {
                          $q->where('target_audience', 'all')
                            ->orWhere('target_audience', 'parents');
                      });
            } else {
                $childrenClasses = $parent->students->pluck('class_id');
                $query->where(function ($q) use ($childrenClasses) {
                    $q->whereNull('class_id')
                      ->orWhereIn('class_id', $childrenClasses);
                })->where(function ($q) {
                    $q->where('target_audience', 'all')
                      ->orWhere('target_audience', 'parents');
                });
            }
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('target_audience')) {
            $query->where('target_audience', $request->target_audience);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'published') {
                $query->published();
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Order by priority and creation date
        $announcements = $query->byPriority()
                              ->orderBy('created_at', 'desc')
                              ->paginate(15);

        $classes = SchoolClass::all();
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $audiences = ['all', 'students', 'teachers', 'parents', 'staff'];

        return view('announcements.index', compact('announcements', 'classes', 'priorities', 'audiences'));
    }

    /**
     * Show the form for creating a new announcement
     */
    public function create()
    {
        $this->authorize('create', Announcement::class);

        $classes = SchoolClass::all();
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $audiences = ['all', 'students', 'teachers', 'parents', 'staff'];

        return view('announcements.create', compact('classes', 'priorities', 'audiences'));
    }

    /**
     * Store a newly created announcement
     */
    public function store(Request $request)
    {
        $this->authorize('create', Announcement::class);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_audience' => 'required|in:all,students,teachers,parents,staff',
            'class_id' => 'nullable|exists:classes,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'publish_date' => 'nullable|date|after_or_equal:now',
            'expire_date' => 'nullable|date|after:publish_date',
            'is_published' => 'boolean',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['created_by'] = Auth::id();

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('announcements', $filename, 'public');
            $data['attachment'] = $filename;
        }

        Announcement::create($data);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified announcement (public access)
     */
    public function publicShow(Announcement $announcement)
    {
        // Check if announcement is public and published
        if ($announcement->target_audience !== 'all' || !$announcement->is_published) {
            abort(404, 'Announcement not found or not accessible.');
        }

        return view('announcements.show', compact('announcement'));
    }

    /**
     * Display the specified announcement (authenticated access)
     */
    public function show(Announcement $announcement)
    {
        $this->authorize('view', $announcement);

        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement
     */
    public function edit(Announcement $announcement)
    {
        $this->authorize('update', $announcement);

        $classes = SchoolClass::all();
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $audiences = ['all', 'students', 'teachers', 'parents', 'staff'];

        return view('announcements.edit', compact('announcement', 'classes', 'priorities', 'audiences'));
    }

    /**
     * Update the specified announcement
     */
    public function update(Request $request, Announcement $announcement)
    {
        $this->authorize('update', $announcement);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_audience' => 'required|in:all,students,teachers,parents,staff',
            'class_id' => 'nullable|exists:classes,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'publish_date' => 'nullable|date',
            'expire_date' => 'nullable|date|after:publish_date',
            'is_published' => 'boolean',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except(['attachment']);

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($announcement->attachment) {
                Storage::disk('public')->delete('announcements/' . $announcement->attachment);
            }

            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('announcements', $filename, 'public');
            $data['attachment'] = $filename;
        }

        $announcement->update($data);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified announcement
     */
    public function destroy(Announcement $announcement)
    {
        $this->authorize('delete', $announcement);

        // Delete attachment file if exists
        if ($announcement->attachment) {
            Storage::disk('public')->delete('announcements/' . $announcement->attachment);
        }

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    /**
     * Toggle publish status
     */
    public function togglePublish(Announcement $announcement)
    {
        $this->authorize('update', $announcement);

        $announcement->update([
            'is_published' => !$announcement->is_published
        ]);

        $status = $announcement->is_published ? 'published' : 'unpublished';

        return redirect()->route('announcements.index')
            ->with('success', "Announcement {$status} successfully.");
    }

    /**
     * Download attachment (public access)
     */
    public function publicDownloadAttachment(Announcement $announcement)
    {
        // Check if announcement is public and published
        if ($announcement->target_audience !== 'all' || !$announcement->is_published) {
            abort(404, 'Announcement not found or not accessible.');
        }

        if (!$announcement->hasAttachment()) {
            abort(404);
        }

        $path = storage_path('app/public/announcements/' . $announcement->attachment);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    /**
     * Download attachment (authenticated access)
     */
    public function downloadAttachment(Announcement $announcement)
    {
        if (!$announcement->hasAttachment()) {
            abort(404);
        }

        $path = storage_path('app/public/announcements/' . $announcement->attachment);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    /**
     * Get announcements for dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $query = Announcement::with(['createdBy', 'class']);

        // Filter based on user role
        if ($user->role === 'student') {
            $studentClass = $user->student->class_id;
            $query->where(function ($q) use ($studentClass) {
                $q->whereNull('class_id')
                  ->orWhere('class_id', $studentClass);
            })->where(function ($q) {
                $q->where('target_audience', 'all')
                  ->orWhere('target_audience', 'students');
            });
        } elseif ($user->role === 'parent') {
            $parent = $user->parent;
            if (!$parent) {
                // If no parent record, show only general announcements
                $query->whereNull('class_id')
                      ->where(function ($q) {
                          $q->where('target_audience', 'all')
                            ->orWhere('target_audience', 'parents');
                      });
            } else {
                $childrenClasses = $parent->students->pluck('class_id');
                $query->where(function ($q) use ($childrenClasses) {
                    $q->whereNull('class_id')
                      ->orWhereIn('class_id', $childrenClasses);
                })->where(function ($q) {
                    $q->where('target_audience', 'all')
                      ->orWhere('target_audience', 'parents');
                });
            }
        } elseif ($user->role === 'teacher') {
            $teacherClasses = $user->teacher->classes->pluck('id');
            $query->where(function ($q) use ($teacherClasses) {
                $q->whereNull('class_id')
                  ->orWhereIn('class_id', $teacherClasses);
            });
        }

        $announcements = $query->active()
                              ->byPriority()
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get();

        return response()->json($announcements);
    }
}
