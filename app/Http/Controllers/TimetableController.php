<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $query = Timetable::with(['class', 'subject', 'teacher']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        $timetables = $query->orderBy('day_of_week')
                           ->orderBy('start_time')
                           ->paginate(20);

        $classes = SchoolClass::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        return view('timetable.index', compact('timetables', 'classes', 'teachers', 'days'));
    }

    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        return view('timetable.create', compact('classes', 'subjects', 'teachers', 'days'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $conflicts = $this->checkConflicts(
            $request->class_id,
            $request->teacher_id,
            $request->day_of_week,
            $request->start_time,
            $request->end_time
        );

        if (!empty($conflicts)) {
            return back()->withErrors($conflicts)->withInput();
        }

        Timetable::create($request->all());

        return redirect()->route('timetable.index')
                        ->with('success', 'Timetable entry created successfully.');
    }

    public function show(Timetable $timetable)
    {
        $timetable->load(['class', 'subject', 'teacher']);
        return view('timetable.show', compact('timetable'));
    }

    public function edit(Timetable $timetable)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        return view('timetable.edit', compact('timetable', 'classes', 'subjects', 'teachers', 'days'));
    }

    public function update(Request $request, Timetable $timetable)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $conflicts = $this->checkConflicts(
            $request->class_id,
            $request->teacher_id,
            $request->day_of_week,
            $request->start_time,
            $request->end_time,
            $timetable->id
        );

        if (!empty($conflicts)) {
            return back()->withErrors($conflicts)->withInput();
        }

        $timetable->update($request->all());

        return redirect()->route('timetable.index')
                        ->with('success', 'Timetable entry updated successfully.');
    }

    public function destroy(Timetable $timetable)
    {
        $timetable->delete();

        return redirect()->route('timetable.index')
                        ->with('success', 'Timetable entry deleted successfully.');
    }

    private function checkConflicts($classId, $teacherId, $dayOfWeek, $startTime, $endTime, $excludeId = null)
    {
        $errors = [];

        $classConflict = Timetable::where('class_id', $classId)
                                 ->where('day_of_week', $dayOfWeek)
                                 ->where(function ($query) use ($startTime, $endTime) {
                                     $query->where(function ($q) use ($startTime, $endTime) {
                                         $q->where('start_time', '<', $endTime)
                                           ->where('end_time', '>', $startTime);
                                     });
                                 });

        if ($excludeId) {
            $classConflict->where('id', '!=', $excludeId);
        }

        if ($classConflict->exists()) {
            $errors['class_conflict'] = 'This class already has a lesson scheduled during this time period.';
        }

        $teacherConflict = Timetable::where('teacher_id', $teacherId)
                                   ->where('day_of_week', $dayOfWeek)
                                   ->where(function ($query) use ($startTime, $endTime) {
                                       $query->where(function ($q) use ($startTime, $endTime) {
                                           $q->where('start_time', '<', $endTime)
                                             ->where('end_time', '>', $startTime);
                                       });
                                   });

        if ($excludeId) {
            $teacherConflict->where('id', '!=', $excludeId);
        }

        if ($teacherConflict->exists()) {
            $errors['teacher_conflict'] = 'This teacher already has a lesson scheduled during this time period.';
        }

        return $errors;
    }
}
