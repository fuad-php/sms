<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\ExamResult;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ExamManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,teacher');
    }

    /**
     * Exam Management Dashboard
     */
    public function dashboard(Request $request)
    {
        $query = Exam::with(['class', 'subject', 'createdBy', 'approvedBy']);

        // Filters
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('exam_session')) {
            $query->where('exam_session', $request->exam_session);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('exam_type')) {
            $query->where('exam_type', $request->exam_type);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('exam_code', 'like', "%{$search}%")
                  ->orWhereHas('class', function($classQuery) use ($search) {
                      $classQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('subject', function($subjectQuery) use ($search) {
                      $subjectQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $exams = $query->orderBy('exam_date', 'desc')->paginate(20);
        
        // Statistics
        $stats = [
            'total_exams' => Exam::count(),
            'draft_exams' => Exam::where('status', 'draft')->count(),
            'pending_exams' => Exam::where('status', 'pending')->count(),
            'approved_exams' => Exam::where('status', 'approved')->count(),
            'completed_exams' => Exam::where('status', 'completed')->count(),
            'online_exams' => Exam::where('is_online', true)->count(),
            'upcoming_exams' => Exam::upcoming()->count(),
            'by_status' => Exam::selectRaw('status, COUNT(*) as count')
                               ->groupBy('status')
                               ->pluck('count', 'status'),
            'by_type' => Exam::selectRaw('exam_type, COUNT(*) as count')
                             ->groupBy('exam_type')
                             ->pluck('count', 'exam_type'),
        ];

        $academicYears = Exam::distinct()->pluck('academic_year')->filter();
        $examSessions = Exam::distinct()->pluck('exam_session')->filter();
        $classes = SchoolClass::active()->get();
        $subjects = Subject::all();

        return view('exams.dashboard', compact('exams', 'stats', 'academicYears', 'examSessions', 'classes', 'subjects'));
    }

    /**
     * Show exam details
     */
    public function show(Exam $exam)
    {
        $exam->load(['class', 'subject', 'createdBy', 'approvedBy', 'results.student.user']);
        
        $statistics = $exam->getStatistics();
        
        return view('exams.show', compact('exam', 'statistics'));
    }

    /**
     * Create new exam
     */
    public function create()
    {
        $subjects = Subject::all();
        $classes = SchoolClass::active()->get();
        
        return view('exams.create', compact('subjects', 'classes'));
    }

    /**
     * Store new exam
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
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0|max:total_marks',
            'exam_type' => 'required|in:written,oral,practical,online,assignment',
            'academic_year' => 'nullable|string',
            'exam_session' => 'nullable|string',
            'exam_venue' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'weightage' => 'nullable|numeric|min:0|max:100',
            'is_online' => 'boolean',
            'max_attempts' => 'nullable|integer|min:1',
            'late_submission_allowed' => 'boolean',
            'late_submission_penalty' => 'nullable|numeric|min:0',
            'grade_scale' => 'nullable|string',
            'negative_marking' => 'boolean',
            'negative_marking_ratio' => 'nullable|numeric|min:0|max:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

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
                'academic_year' => $request->academic_year ?? now()->year,
                'exam_session' => $request->exam_session,
                'exam_venue' => $request->exam_venue,
                'instructions' => $request->instructions,
                'duration_minutes' => $request->duration_minutes,
                'weightage' => $request->weightage ?? 100.00,
                'is_online' => $request->boolean('is_online'),
                'max_attempts' => $request->max_attempts ?? 1,
                'late_submission_allowed' => $request->boolean('late_submission_allowed'),
                'late_submission_penalty' => $request->late_submission_penalty ?? 0.00,
                'created_by' => auth()->id(),
                'status' => 'draft',
                'grade_scale' => $request->grade_scale ?? 'A+,A,A-,B+,B,B-,C+,C,C-,D,F',
                'negative_marking' => $request->boolean('negative_marking'),
                'negative_marking_ratio' => $request->negative_marking_ratio ?? 0.25,
            ]);

            // Generate exam code
            $exam->generateExamCode();

            DB::commit();

            return redirect()->route('exams.dashboard')
                           ->with('success', 'Exam created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Exam creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create exam: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Edit exam
     */
    public function edit(Exam $exam)
    {
        if (!$exam->canBeEdited()) {
            return back()->withErrors(['error' => 'This exam cannot be edited.']);
        }

        $exam->load(['class', 'subject']);
        $subjects = Subject::all();
        $classes = SchoolClass::active()->get();
        
        return view('exams.edit', compact('exam', 'subjects', 'classes'));
    }

    /**
     * Update exam
     */
    public function update(Request $request, Exam $exam)
    {
        if (!$exam->canBeEdited()) {
            return back()->withErrors(['error' => 'This exam cannot be edited.']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0|max:total_marks',
            'exam_type' => 'required|in:written,oral,practical,online,assignment',
            'academic_year' => 'nullable|string',
            'exam_session' => 'nullable|string',
            'exam_venue' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'weightage' => 'nullable|numeric|min:0|max:100',
            'is_online' => 'boolean',
            'max_attempts' => 'nullable|integer|min:1',
            'late_submission_allowed' => 'boolean',
            'late_submission_penalty' => 'nullable|numeric|min:0',
            'grade_scale' => 'nullable|string',
            'negative_marking' => 'boolean',
            'negative_marking_ratio' => 'nullable|numeric|min:0|max:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

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
                'academic_year' => $request->academic_year,
                'exam_session' => $request->exam_session,
                'exam_venue' => $request->exam_venue,
                'instructions' => $request->instructions,
                'duration_minutes' => $request->duration_minutes,
                'weightage' => $request->weightage,
                'is_online' => $request->boolean('is_online'),
                'max_attempts' => $request->max_attempts,
                'late_submission_allowed' => $request->boolean('late_submission_allowed'),
                'late_submission_penalty' => $request->late_submission_penalty,
                'grade_scale' => $request->grade_scale,
                'negative_marking' => $request->boolean('negative_marking'),
                'negative_marking_ratio' => $request->negative_marking_ratio,
            ]);

            DB::commit();

            return redirect()->route('exams.show', $exam)
                           ->with('success', 'Exam updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Exam update failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update exam: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Delete exam
     */
    public function destroy(Exam $exam)
    {
        if (!$exam->canBeDeleted()) {
            return back()->withErrors(['error' => 'This exam cannot be deleted.']);
        }

        try {
            $exam->delete();
            return redirect()->route('exams.dashboard')
                           ->with('success', 'Exam deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Exam deletion failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete exam: ' . $e->getMessage()]);
        }
    }

    /**
     * Submit exam for approval
     */
    public function submitForApproval(Exam $exam)
    {
        if ($exam->status !== 'draft') {
            return back()->withErrors(['error' => 'Only draft exams can be submitted for approval.']);
        }

        try {
            $exam->update(['status' => 'pending']);
            return back()->with('success', 'Exam submitted for approval successfully.');

        } catch (\Exception $e) {
            Log::error('Exam submission failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to submit exam: ' . $e->getMessage()]);
        }
    }

    /**
     * Approve exam
     */
    public function approve(Exam $exam)
    {
        if ($exam->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending exams can be approved.']);
        }

        try {
            $exam->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approval_date' => now(),
            ]);

            return back()->with('success', 'Exam approved successfully.');

        } catch (\Exception $e) {
            Log::error('Exam approval failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to approve exam: ' . $e->getMessage()]);
        }
    }

    /**
     * Reject exam
     */
    public function reject(Request $request, Exam $exam)
    {
        if ($exam->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending exams can be rejected.']);
        }

        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $exam->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approval_date' => now(),
                'notes' => $request->rejection_reason,
            ]);

            return back()->with('success', 'Exam rejected successfully.');

        } catch (\Exception $e) {
            Log::error('Exam rejection failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to reject exam: ' . $e->getMessage()]);
        }
    }

    /**
     * Bulk operations
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject,archive,delete',
            'exam_ids' => 'required|array|min:1',
            'exam_ids.*' => 'exists:exams,id',
            'rejection_reason' => 'nullable|string|required_if:action,reject',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            DB::beginTransaction();

            $exams = Exam::whereIn('id', $request->exam_ids);

            switch ($request->action) {
                case 'approve':
                    $exams->where('status', 'pending')->update([
                        'status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approval_date' => now(),
                    ]);
                    $message = 'Exams approved successfully.';
                    break;
                case 'reject':
                    $exams->where('status', 'pending')->update([
                        'status' => 'rejected',
                        'approved_by' => auth()->id(),
                        'approval_date' => now(),
                        'notes' => $request->rejection_reason,
                    ]);
                    $message = 'Exams rejected successfully.';
                    break;
                case 'archive':
                    $exams->update(['is_archived' => true]);
                    $message = 'Exams archived successfully.';
                    break;
                case 'delete':
                    $exams->where('status', 'draft')->delete();
                    $message = 'Draft exams deleted successfully.';
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

    /**
     * Exam analytics and reports
     */
    public function analytics(Request $request)
    {
        $query = Exam::with(['class', 'subject', 'results']);

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $exams = $query->get();

        $analytics = [
            'total_exams' => $exams->count(),
            'average_marks' => $exams->avg(function($exam) {
                return $exam->getAverageMarks();
            }),
            'average_pass_percentage' => $exams->avg(function($exam) {
                return $exam->getPassPercentage();
            }),
            'by_status' => $exams->groupBy('status')->map->count(),
            'by_type' => $exams->groupBy('exam_type')->map->count(),
            'by_class' => $exams->groupBy('class.name')->map->count(),
            'performance_trends' => $exams->map(function($exam) {
                return [
                    'exam_name' => $exam->name,
                    'class' => $exam->class->name,
                    'subject' => $exam->subject->name,
                    'average_marks' => $exam->getAverageMarks(),
                    'pass_percentage' => $exam->getPassPercentage(),
                    'total_students' => $exam->results()->count(),
                ];
            }),
        ];

        $classes = SchoolClass::active()->get();
        $academicYears = Exam::distinct()->pluck('academic_year')->filter();

        return view('exams.analytics', compact('analytics', 'classes', 'academicYears'));
    }
}
