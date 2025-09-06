<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class ReportCardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,teacher,student,parent');
    }

    /**
     * Selector page for choosing a student to view report card
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'student' && $user->student) {
            return redirect()->route('students.report-card', $user->student->id);
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

        return view('reports.report-card-select', compact('students'));
    }

    /**
     * Show a printable report card for a student (current academic year)
     */
    public function show($studentId)
    {
        $student = Student::with(['user', 'class'])->findOrFail($studentId);

        // Authorization: students can view self; parents can view their children
        $user = auth()->user();
        if ($user->role === 'student' && optional($user->student)->id !== $student->id) {
            abort(403);
        }
        if ($user->role === 'parent' && !$user->parent?->students->pluck('id')->contains($student->id)) {
            abort(403);
        }

        $results = ExamResult::with(['exam.subject'])
            ->where('student_id', $student->id)
            ->orderByDesc('created_at')
            ->get();

        $bySubject = $results->groupBy(function ($r) {
            return $r->exam->subject->name;
        });

        $average = $results->filter(function ($r) {
            return !$r->is_absent;
        })->map(function ($r) {
            return $r->getPercentage();
        })->avg();

        $summary = [
            'exams_count' => $results->count(),
            'average_percentage' => $average,
            'pass_rate' => $results->count() > 0 ? round(($results->filter->isPassed()->count() / $results->count()) * 100, 2) : 0,
        ];

        return view('reports.report-card', compact('student', 'results', 'bySubject', 'summary'));
    }
}
