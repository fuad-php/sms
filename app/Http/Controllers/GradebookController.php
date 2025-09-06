<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\SchoolClass;
use App\Models\Subject;

class GradebookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,teacher');
    }

    /**
     * Gradebook by class and subject
     */
    public function index(Request $request)
    {
        $classes = SchoolClass::active()->get();
        $subjects = Subject::all();

        $classId = $request->get('class_id');
        $subjectId = $request->get('subject_id');

        $exams = collect();
        $resultsByStudent = collect();

        if ($classId && $subjectId) {
            $exams = Exam::where('class_id', $classId)
                ->where('subject_id', $subjectId)
                ->orderBy('exam_date', 'desc')
                ->get();

            $resultsByStudent = ExamResult::with(['student.user', 'exam'])
                ->whereIn('exam_id', $exams->pluck('id'))
                ->get()
                ->groupBy('student_id');
        }

        return view('gradebook.index', compact('classes', 'subjects', 'exams', 'resultsByStudent', 'classId', 'subjectId'));
    }
}
