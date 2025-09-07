<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use App\Models\Room;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $query = Timetable::with(['class', 'section', 'subject', 'teacher', 'room']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        $timetables = $query->orderBy('day_of_week')
                           ->orderBy('start_time')
                           ->paginate(20);

        $classes = SchoolClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $rooms = Room::where('is_active', true)->orderBy('name')->get();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        return view('timetable.index', compact('timetables', 'classes', 'sections', 'teachers', 'rooms', 'days'));
    }

    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $rooms = Room::where('is_active', true)->orderBy('name')->get();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        return view('timetable.create', compact('classes', 'sections', 'subjects', 'teachers', 'rooms', 'days'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_id' => 'nullable|exists:rooms,id',
            'room' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $conflicts = $this->checkConflicts(
            $validated['class_id'],
            $validated['section_id'] ?? null,
            $validated['teacher_id'],
            $validated['room_id'] ?? null,
            $validated['day_of_week'],
            $validated['start_time'],
            $validated['end_time']
        );

        if (!empty($conflicts)) {
            return back()->withErrors($conflicts)->withInput();
        }

        Timetable::create($validated);

        return redirect()->route('timetable.index')
                        ->with('success', 'Timetable entry created successfully.');
    }

    public function show(Timetable $timetable)
    {
        $timetable->load(['class', 'section', 'subject', 'teacher', 'room']);
        return view('timetable.show', compact('timetable'));
    }

    public function edit(Timetable $timetable)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $rooms = Room::where('is_active', true)->orderBy('name')->get();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        return view('timetable.edit', compact('timetable', 'classes', 'sections', 'subjects', 'teachers', 'rooms', 'days'));
    }

    public function update(Request $request, Timetable $timetable)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_id' => 'nullable|exists:rooms,id',
            'room' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $conflicts = $this->checkConflicts(
            $validated['class_id'],
            $validated['section_id'] ?? null,
            $validated['teacher_id'],
            $validated['room_id'] ?? null,
            $validated['day_of_week'],
            $validated['start_time'],
            $validated['end_time'],
            $timetable->id
        );

        if (!empty($conflicts)) {
            return back()->withErrors($conflicts)->withInput();
        }

        $timetable->update($validated);

        return redirect()->route('timetable.index')
                        ->with('success', 'Timetable entry updated successfully.');
    }

    public function destroy(Timetable $timetable)
    {
        $timetable->delete();

        return redirect()->route('timetable.index')
                        ->with('success', 'Timetable entry deleted successfully.');
    }

    private function checkConflicts($classId, $sectionId, $teacherId, $roomId, $dayOfWeek, $startTime, $endTime, $excludeId = null)
    {
        $errors = [];

        // Class/Section conflict: if sectionId is null, conflict with any section; else conflict with same section or entries without section
        $classConflict = Timetable::where('class_id', $classId)
                                 ->where('day_of_week', $dayOfWeek)
                                 ->when($sectionId !== null, function ($q) use ($sectionId) {
                                     $q->where(function($qq) use ($sectionId) {
                                         $qq->where('section_id', $sectionId)
                                            ->orWhereNull('section_id');
                                     });
                                 }, function ($q) {
                                     $q->whereNull('section_id');
                                 })
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

        if ($roomId) {
            $roomConflict = Timetable::where('room_id', $roomId)
                                     ->where('day_of_week', $dayOfWeek)
                                     ->where(function ($query) use ($startTime, $endTime) {
                                         $query->where(function ($q) use ($startTime, $endTime) {
                                             $q->where('start_time', '<', $endTime)
                                               ->where('end_time', '>', $startTime);
                                         });
                                     });

            if ($excludeId) {
                $roomConflict->where('id', '!=', $excludeId);
            }

            if ($roomConflict->exists()) {
                $errors['room_conflict'] = 'This room is already booked during this time period.';
            }
        }

        return $errors;
    }
}
