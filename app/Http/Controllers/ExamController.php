<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,teacher')->except(['index', 'show']);
    }

    /**
     * Display a listing of exams
     */
    public function index(Request $request)
    {
        $query = Exam::with(['class', 'subject']);

        // Filter by class
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        // Filter by subject
        if ($request->has('subject_id') && $request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by exam type
        if ($request->has('exam_type') && $request->exam_type) {
            $query->where('exam_type', $request->exam_type);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            switch ($request->status) {
                case 'upcoming':
                    $query->upcoming();
                    break;
                case 'past':
                    $query->past();
                    break;
                case 'today':
                    $query->whereDate('exam_date', now()->toDateString());
                    break;
                case 'published':
                    $query->published();
                    break;
                case 'unpublished':
                    $query->where('is_published', false);
                    break;
            }
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('class', function ($classQuery) use ($search) {
                      $classQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('subject', function ($subjectQuery) use ($search) {
                      $subjectQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Order by exam date
        $query->orderBy('exam_date', 'desc')->orderBy('start_time');

        // Pagination
        $perPage = $request->get('per_page', 15);
        $exams = $query->paginate($perPage);

        // Get statistics
        $stats = [
            'total_exams' => Exam::count(),
            'upcoming_exams' => Exam::upcoming()->count(),
            'past_exams' => Exam::past()->count(),
            'today_exams' => Exam::whereDate('exam_date', now()->toDateString())->count(),
            'published_exams' => Exam::published()->count(),
        ];

        // Get filter options
        $classes = SchoolClass::where('is_active', true)->orderBy('name')->get();
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        $examTypes = ['midterm', 'final', 'quiz', 'assignment', 'project', 'practical'];

        return view('exams.index', compact('exams', 'stats', 'classes', 'subjects', 'examTypes'));
    }

    /**
     * Show the form for creating a new exam
     */
    public function create()
    {
        $classes = SchoolClass::where('is_active', true)->orderBy('name')->get();
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        $examTypes = ['midterm', 'final', 'quiz', 'assignment', 'project', 'practical'];

        return view('exams.create', compact('classes', 'subjects', 'examTypes'));
    }

    /**
     * Store a newly created exam
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'total_marks' => 'required|integer|min:1|max:1000',
            'passing_marks' => 'required|integer|min:0|max:1000',
            'exam_type' => 'required|in:midterm,final,quiz,assignment,project,practical',
            'is_published' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Validate passing marks is not greater than total marks
        if ($request->passing_marks > $request->total_marks) {
            return back()->withErrors(['passing_marks' => 'Passing marks cannot be greater than total marks.'])->withInput();
        }

        try {
            $exam = Exam::create([
                'name' => $request->name,
                'description' => $request->description,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'exam_date' => $request->exam_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'total_marks' => $request->total_marks,
                'passing_marks' => $request->passing_marks,
                'exam_type' => $request->exam_type,
                'is_published' => $request->has('is_published'),
            ]);

            return redirect()->route('exams.show', $exam)
                           ->with('success', 'Exam created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create exam: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Display the specified exam
     */
    public function show(Exam $exam)
    {
        $exam->load(['class', 'subject', 'results.student.user']);
        
        // Get exam statistics
        $stats = [
            'results_count' => $exam->getResultsCount(),
            'expected_results' => $exam->getExpectedResultsCount(),
            'completion_percentage' => $exam->getResultsCompletionPercentage(),
            'average_marks' => $exam->getAverageMarks(),
            'pass_percentage' => $exam->getPassPercentage(),
            'duration' => $exam->getDurationFormatted(),
        ];

        // Get recent results
        $recentResults = $exam->results()
                             ->with(['student.user'])
                             ->orderBy('created_at', 'desc')
                             ->limit(10)
                             ->get();

        return view('exams.show', compact('exam', 'stats', 'recentResults'));
    }

    /**
     * Show the form for editing the specified exam
     */
    public function edit(Exam $exam)
    {
        $classes = SchoolClass::where('is_active', true)->orderBy('name')->get();
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        $examTypes = ['midterm', 'final', 'quiz', 'assignment', 'project', 'practical'];

        return view('exams.edit', compact('exam', 'classes', 'subjects', 'examTypes'));
    }

    /**
     * Update the specified exam
     */
    public function update(Request $request, Exam $exam)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'total_marks' => 'required|integer|min:1|max:1000',
            'passing_marks' => 'required|integer|min:0|max:1000',
            'exam_type' => 'required|in:midterm,final,quiz,assignment,project,practical',
            'is_published' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Validate passing marks is not greater than total marks
        if ($request->passing_marks > $request->total_marks) {
            return back()->withErrors(['passing_marks' => 'Passing marks cannot be greater than total marks.'])->withInput();
        }

        try {
            $exam->update([
                'name' => $request->name,
                'description' => $request->description,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'exam_date' => $request->exam_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'total_marks' => $request->total_marks,
                'passing_marks' => $request->passing_marks,
                'exam_type' => $request->exam_type,
                'is_published' => $request->has('is_published'),
            ]);

            return redirect()->route('exams.show', $exam)
                           ->with('success', 'Exam updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update exam: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Remove the specified exam
     */
    public function destroy(Exam $exam)
    {
        try {
            // Check if exam has results
            if ($exam->results()->count() > 0) {
                return back()->withErrors(['error' => 'Cannot delete exam with existing results.']);
            }

            $exam->delete();

            return redirect()->route('exams.index')
                           ->with('success', 'Exam deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete exam: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle exam publish status
     */
    public function togglePublish(Exam $exam)
    {
        try {
            $exam->update(['is_published' => !$exam->is_published]);
            
            $status = $exam->is_published ? 'published' : 'unpublished';
            return back()->with('success', "Exam {$status} successfully.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update exam status: ' . $e->getMessage()]);
        }
    }

    /**
     * Get exam statistics
     */
    public function statistics()
    {
        $stats = [
            'total_exams' => Exam::count(),
            'upcoming_exams' => Exam::upcoming()->count(),
            'past_exams' => Exam::past()->count(),
            'today_exams' => Exam::whereDate('exam_date', now()->toDateString())->count(),
            'published_exams' => Exam::published()->count(),
            'unpublished_exams' => Exam::where('is_published', false)->count(),
        ];

        // Exam type statistics
        $examTypeStats = Exam::select('exam_type', DB::raw('count(*) as count'))
                            ->groupBy('exam_type')
                            ->get()
                            ->pluck('count', 'exam_type');

        // Monthly exam statistics
        $monthlyStats = Exam::select(DB::raw('MONTH(exam_date) as month'), DB::raw('count(*) as count'))
                           ->whereYear('exam_date', now()->year)
                           ->groupBy('month')
                           ->get()
                           ->pluck('count', 'month');

        return response()->json([
            'stats' => $stats,
            'exam_type_stats' => $examTypeStats,
            'monthly_stats' => $monthlyStats,
        ]);
    }

    /**
     * Get upcoming exams for dashboard
     */
    public function getUpcomingExams(Request $request)
    {
        $limit = $request->get('limit', 5);
        
        $exams = Exam::with(['class', 'subject'])
                    ->upcoming()
                    ->published()
                    ->orderBy('exam_date')
                    ->orderBy('start_time')
                    ->limit($limit)
                    ->get();

        return response()->json($exams);
    }

    /**
     * Get exams by class
     */
    public function getByClass(SchoolClass $class)
    {
        $exams = Exam::with(['subject'])
                    ->where('class_id', $class->id)
                    ->orderBy('exam_date', 'desc')
                    ->orderBy('start_time')
                    ->get();

        return response()->json($exams);
    }

    /**
     * Get exams by subject
     */
    public function getBySubject(Subject $subject)
    {
        $exams = Exam::with(['class'])
                    ->where('subject_id', $subject->id)
                    ->orderBy('exam_date', 'desc')
                    ->orderBy('start_time')
                    ->get();

        return response()->json($exams);
    }
}
