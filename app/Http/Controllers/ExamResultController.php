<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ExamResultController extends Controller
{
    /**
     * Display a listing of exam results.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', ExamResult::class);

        $user = auth()->user();
        $query = ExamResult::with(['exam.subject', 'exam.class', 'student.user']);

        // Apply filters based on user role and permissions
        if ($user->role === 'student' && $user->student) {
            $query->where('student_id', $user->student->id);
        } elseif ($user->role === 'parent' && $user->parent) {
            $studentIds = $user->parent->students->pluck('id');
            $query->whereIn('student_id', $studentIds);
        } elseif ($user->role === 'teacher') {
            // Teachers can see results for subjects they teach
            $teacherClasses = $user->teacher->subjects()->pluck('class_id')->unique();
            $teacherSubjects = $user->teacher->subjects()->pluck('subject_id')->unique();
            $query->whereHas('exam', function ($q) use ($teacherClasses, $teacherSubjects) {
                $q->whereIn('class_id', $teacherClasses)
                  ->whereIn('subject_id', $teacherSubjects);
            });
        }

        // Apply filters
        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }
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
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'passed':
                    $query->where('is_absent', false)->whereRaw('marks_obtained >= (SELECT passing_marks FROM exams WHERE exams.id = exam_results.exam_id)');
                    break;
                case 'failed':
                    $query->where('is_absent', false)->whereRaw('marks_obtained < (SELECT passing_marks FROM exams WHERE exams.id = exam_results.exam_id)');
                    break;
                case 'absent':
                    $query->where('is_absent', true);
                    break;
            }
        }

        $results = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $exams = Exam::with(['class', 'subject'])->orderBy('exam_date', 'desc')->get();
        $classes = SchoolClass::active()->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $students = Student::with('user')->active()->orderBy('roll_number')->get();

        return view('results.index', compact('results', 'exams', 'classes', 'subjects', 'students'));
    }

    /**
     * Show the form for creating a new exam result.
     */
    public function create(Request $request)
    {
        $this->authorize('create', ExamResult::class);

        $user = auth()->user();
        
        // Get available exams
        $examQuery = Exam::with(['class', 'subject']);
        if ($user->role === 'teacher') {
            $teacherClasses = $user->teacher->subjects()->pluck('class_id')->unique();
            $teacherSubjects = $user->teacher->subjects()->pluck('subject_id')->unique();
            $examQuery->whereIn('class_id', $teacherClasses)
                     ->whereIn('subject_id', $teacherSubjects);
        }
        $exams = $examQuery->published()->orderBy('exam_date', 'desc')->get();

        // Get students for the selected exam
        $students = collect();
        if ($request->filled('exam_id')) {
            $exam = Exam::find($request->exam_id);
            if ($exam) {
                $students = Student::with('user')
                    ->where('class_id', $exam->class_id)
                    ->active()
                    ->orderBy('roll_number')
                    ->get();
            }
        }

        return view('results.create', compact('exams', 'students'));
    }

    /**
     * Store a newly created exam result in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', ExamResult::class);

        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            'student_id' => 'required|exists:students,id',
            'marks_obtained' => 'nullable|numeric|min:0',
            'grade' => 'nullable|string|max:10',
            'remarks' => 'nullable|string|max:500',
            'is_absent' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if result already exists
        $existingResult = ExamResult::where('exam_id', $request->exam_id)
            ->where('student_id', $request->student_id)
            ->first();

        if ($existingResult) {
            return back()->withErrors(['student_id' => 'Result already exists for this student and exam.'])->withInput();
        }

        // Validate marks against exam total
        if (!$request->is_absent && $request->filled('marks_obtained')) {
            $exam = Exam::find($request->exam_id);
            if ($request->marks_obtained > $exam->total_marks) {
                return back()->withErrors(['marks_obtained' => "Marks cannot exceed total marks ({$exam->total_marks})."])->withInput();
            }
        }

        try {
            DB::beginTransaction();

            $result = ExamResult::create([
                'exam_id' => $request->exam_id,
                'student_id' => $request->student_id,
                'marks_obtained' => $request->is_absent ? null : $request->marks_obtained,
                'grade' => $request->grade,
                'remarks' => $request->remarks,
                'is_absent' => $request->is_absent,
            ]);

            DB::commit();

            return redirect()->route('results.show', $result)
                ->with('success', 'Exam result created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create exam result. Please try again.'])->withInput();
        }
    }

    /**
     * Display the specified exam result.
     */
    public function show(ExamResult $result)
    {
        $this->authorize('view', $result);

        $result->load(['exam.subject', 'exam.class', 'student.user']);
        
        return view('results.show', compact('result'));
    }

    /**
     * Show the form for editing the specified exam result.
     */
    public function edit(ExamResult $result)
    {
        $this->authorize('update', $result);

        $result->load(['exam.subject', 'exam.class', 'student.user']);
        
        return view('results.edit', compact('result'));
    }

    /**
     * Update the specified exam result in storage.
     */
    public function update(Request $request, ExamResult $result)
    {
        $this->authorize('update', $result);

        $validator = Validator::make($request->all(), [
            'marks_obtained' => 'nullable|numeric|min:0',
            'grade' => 'nullable|string|max:10',
            'remarks' => 'nullable|string|max:500',
            'is_absent' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Validate marks against exam total
        if (!$request->is_absent && $request->filled('marks_obtained')) {
            $exam = $result->exam;
            if ($request->marks_obtained > $exam->total_marks) {
                return back()->withErrors(['marks_obtained' => "Marks cannot exceed total marks ({$exam->total_marks})."])->withInput();
            }
        }

        try {
            $result->update([
                'marks_obtained' => $request->is_absent ? null : $request->marks_obtained,
                'grade' => $request->grade,
                'remarks' => $request->remarks,
                'is_absent' => $request->is_absent,
            ]);

            return redirect()->route('results.show', $result)
                ->with('success', 'Exam result updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update exam result. Please try again.'])->withInput();
        }
    }

    /**
     * Remove the specified exam result from storage.
     */
    public function destroy(ExamResult $result)
    {
        $this->authorize('delete', $result);

        try {
            $result->delete();
            return redirect()->route('results.index')
                ->with('success', 'Exam result deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete exam result. Please try again.']);
        }
    }

    /**
     * Bulk import results for an exam.
     */
    public function bulkImport(Request $request)
    {
        $this->authorize('bulkImport', ExamResult::class);

        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            'results' => 'required|array',
            'results.*.student_id' => 'required|exists:students,id',
            'results.*.marks_obtained' => 'nullable|numeric|min:0',
            'results.*.grade' => 'nullable|string|max:10',
            'results.*.remarks' => 'nullable|string|max:500',
            'results.*.is_absent' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $exam = Exam::find($request->exam_id);
        $results = $request->results;

        try {
            DB::beginTransaction();

            foreach ($results as $resultData) {
                // Check if result already exists
                $existingResult = ExamResult::where('exam_id', $request->exam_id)
                    ->where('student_id', $resultData['student_id'])
                    ->first();

                if ($existingResult) {
                    // Update existing result
                    $existingResult->update([
                        'marks_obtained' => $resultData['is_absent'] ? null : $resultData['marks_obtained'],
                        'grade' => $resultData['grade'],
                        'remarks' => $resultData['remarks'],
                        'is_absent' => $resultData['is_absent'],
                    ]);
                } else {
                    // Create new result
                    ExamResult::create([
                        'exam_id' => $request->exam_id,
                        'student_id' => $resultData['student_id'],
                        'marks_obtained' => $resultData['is_absent'] ? null : $resultData['marks_obtained'],
                        'grade' => $resultData['grade'],
                        'remarks' => $resultData['remarks'],
                        'is_absent' => $resultData['is_absent'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('results.index')
                ->with('success', 'Results imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to import results. Please try again.'])->withInput();
        }
    }

    /**
     * Show bulk import form.
     */
    public function showBulkImport(Request $request)
    {
        $this->authorize('bulkImport', ExamResult::class);

        $user = auth()->user();
        
        // Get available exams
        $examQuery = Exam::with(['class', 'subject']);
        if ($user->role === 'teacher') {
            $teacherClasses = $user->teacher->subjects()->pluck('class_id')->unique();
            $teacherSubjects = $user->teacher->subjects()->pluck('subject_id')->unique();
            $examQuery->whereIn('class_id', $teacherClasses)
                     ->whereIn('subject_id', $teacherSubjects);
        }
        $exams = $examQuery->published()->orderBy('exam_date', 'desc')->get();

        $students = collect();
        if ($request->filled('exam_id')) {
            $exam = Exam::find($request->exam_id);
            if ($exam) {
                $students = Student::with('user')
                    ->where('class_id', $exam->class_id)
                    ->active()
                    ->orderBy('roll_number')
                    ->get();
            }
        }

        return view('results.bulk-import', compact('exams', 'students'));
    }

    /**
     * Export results.
     */
    public function export(Request $request)
    {
        $this->authorize('export', ExamResult::class);

        $user = auth()->user();
        $query = ExamResult::with(['exam.subject', 'exam.class', 'student.user']);

        // Apply filters based on user role
        if ($user->role === 'teacher') {
            $teacherClasses = $user->teacher->subjects()->pluck('class_id')->unique();
            $teacherSubjects = $user->teacher->subjects()->pluck('subject_id')->unique();
            $query->whereHas('exam', function ($q) use ($teacherClasses, $teacherSubjects) {
                $q->whereIn('class_id', $teacherClasses)
                  ->whereIn('subject_id', $teacherSubjects);
            });
        }

        // Apply filters
        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }
        if ($request->filled('class_id')) {
            $query->whereHas('exam', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        $results = $query->get();

        // Generate CSV
        $filename = 'exam_results_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($results) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Student ID', 'Student Name', 'Class', 'Subject', 'Exam', 'Date',
                'Marks Obtained', 'Total Marks', 'Percentage', 'Grade', 'Status', 'Remarks'
            ]);

            foreach ($results as $result) {
                $percentage = $result->getPercentage();
                $status = $result->is_absent ? 'Absent' : ($result->isPassed() ? 'Passed' : 'Failed');
                
                fputcsv($file, [
                    $result->student->student_id,
                    $result->student->user->name,
                    $result->exam->class->name,
                    $result->exam->subject->name,
                    $result->exam->name,
                    $result->exam->exam_date->format('Y-m-d'),
                    $result->marks_obtained ?? 'N/A',
                    $result->exam->total_marks,
                    $percentage ? $percentage . '%' : 'N/A',
                    $result->getGradeCalculated(),
                    $status,
                    $result->remarks ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show results statistics.
     */
    public function statistics(Request $request)
    {
        $this->authorize('viewAny', ExamResult::class);

        $user = auth()->user();
        $query = ExamResult::with(['exam.subject', 'exam.class']);

        // Apply filters based on user role
        if ($user->role === 'student' && $user->student) {
            $query->where('student_id', $user->student->id);
        } elseif ($user->role === 'parent' && $user->parent) {
            $studentIds = $user->parent->students->pluck('id');
            $query->whereIn('student_id', $studentIds);
        } elseif ($user->role === 'teacher') {
            $teacherClasses = $user->teacher->subjects()->pluck('class_id')->unique();
            $teacherSubjects = $user->teacher->subjects()->pluck('subject_id')->unique();
            $query->whereHas('exam', function ($q) use ($teacherClasses, $teacherSubjects) {
                $q->whereIn('class_id', $teacherClasses)
                  ->whereIn('subject_id', $teacherSubjects);
            });
        }

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

        $results = $query->get();

        // Calculate statistics
        $totalResults = $results->count();
        $presentResults = $results->where('is_absent', false);
        $absentResults = $results->where('is_absent', true);
        $passedResults = $presentResults->filter(function ($result) {
            return $result->isPassed();
        });
        $failedResults = $presentResults->filter(function ($result) {
            return $result->isFailed();
        });

        $statistics = [
            'total' => $totalResults,
            'present' => $presentResults->count(),
            'absent' => $absentResults->count(),
            'passed' => $passedResults->count(),
            'failed' => $failedResults->count(),
            'pass_percentage' => $presentResults->count() > 0 ? 
                round(($passedResults->count() / $presentResults->count()) * 100, 2) : 0,
            'average_marks' => $presentResults->count() > 0 ? 
                round($presentResults->avg('marks_obtained'), 2) : 0,
        ];

        // Grade distribution
        $gradeDistribution = $presentResults->groupBy('grade')->map(function ($group) {
            return $group->count();
        });

        // Class-wise performance
        $classPerformance = $results->groupBy('exam.class.name')->map(function ($classResults) {
            $present = $classResults->where('is_absent', false);
            $passed = $present->filter(function ($result) {
                return $result->isPassed();
            });
            
            return [
                'total' => $classResults->count(),
                'present' => $present->count(),
                'passed' => $passed->count(),
                'pass_percentage' => $present->count() > 0 ? 
                    round(($passed->count() / $present->count()) * 100, 2) : 0,
                'average_marks' => $present->count() > 0 ? 
                    round($present->avg('marks_obtained'), 2) : 0,
            ];
        });

        // Get filter options
        $classes = SchoolClass::active()->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('results.statistics', compact(
            'statistics', 
            'gradeDistribution', 
            'classPerformance', 
            'classes', 
            'subjects'
        ));
    }
}
