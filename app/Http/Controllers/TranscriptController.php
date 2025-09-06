<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class TranscriptController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,teacher,student,parent');
    }

    /**
     * Selector page for choosing a student to view transcript
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'student' && $user->student) {
            return redirect()->route('students.transcript', $user->student->id);
        }

        if ($user->role === 'parent' && $user->parent) {
            $students = $user->parent->students()->with('user', 'class')->get();
        } else {
            // admin/teacher
            $query = \App\Models\Student::with('user', 'class')->active();
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                });
            }
            $students = $query->orderBy('id', 'desc')->paginate(20);
        }

        return view('reports.transcript-select', compact('students'));
    }

    /**
     * Show a multi-term transcript for a student.
     */
    public function show($studentId)
    {
        $student = Student::with(['user', 'class'])->findOrFail($studentId);

        $user = auth()->user();
        if ($user->role === 'student' && optional($user->student)->id !== $student->id) {
            abort(403);
        }
        if ($user->role === 'parent' && !$user->parent?->students->pluck('id')->contains($student->id)) {
            abort(403);
        }

        $results = ExamResult::with(['exam.subject', 'exam.class'])
            ->where('student_id', $student->id)
            ->orderBy('exam_id')
            ->get();

        // Group by academic year (derive from exam_date year)
        $byYear = $results->groupBy(function ($r) {
            return optional($r->exam->exam_date)->format('Y') ?? 'Unknown';
        });

        // Simple GPA mapping
        $gradePoints = [
            'A+' => 4.0, 'A' => 4.0,
            'B+' => 3.5, 'B' => 3.0,
            'C+' => 2.5, 'C' => 2.0,
            'D' => 1.0, 'F' => 0.0,
            'AB' => null,
        ];

        $years = $byYear->map(function ($items, $year) use ($gradePoints) {
            $points = $items->map(function ($r) use ($gradePoints) {
                $g = $r->getGradeCalculated();
                return $gradePoints[$g] ?? null;
            })->filter(function ($p) {
                return !is_null($p);
            });

            return [
                'year' => $year,
                'results' => $items,
                'gpa' => $points->count() > 0 ? round($points->avg(), 2) : null,
            ];
        })->values();

        $allPoints = $results->map(function ($r) use ($gradePoints) {
            $g = $r->getGradeCalculated();
            return $gradePoints[$g] ?? null;
        })->filter(function ($p) {
            return !is_null($p);
        });

        $overallGpa = $allPoints->count() > 0 ? round($allPoints->avg(), 2) : null;

        return view('reports.transcript', compact('student', 'years', 'overallGpa'));
    }
}
