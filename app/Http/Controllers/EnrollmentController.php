<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,teacher');
    }

    /**
     * List enrollments for a student
     */
    public function index($studentId)
    {
        $student = Student::with(['user', 'class', 'enrollments.class', 'enrollments.section'])
            ->findOrFail($studentId);

        $classes = SchoolClass::active()->get();
        $sections = Section::all();

        return view('enrollments.index', compact('student', 'classes', 'sections'));
    }

    /**
     * Enroll or update enrollment for a student
     */
    public function store(Request $request, $studentId)
    {
        $student = Student::findOrFail($studentId);

        $data = $request->all();
        $validator = Validator::make($data, [
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'academic_year' => 'required|string',
            'enrolled_at' => 'nullable|date',
            'status' => 'required|in:enrolled,promoted,transferred,graduated,withdrawn',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Enrollment::create([
            'student_id' => $student->id,
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'] ?? null,
            'academic_year' => $data['academic_year'],
            'enrolled_at' => $data['enrolled_at'] ?? now(),
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        // Optionally sync current class on student
        $student->update([
            'class_id' => $data['class_id'],
        ]);

        return redirect()->route('enrollments.index', $student->id)->with('success', 'Enrollment saved.');
    }
}
