<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Timetable;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TimetableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first class for demo
        $class = SchoolClass::first();
        if (!$class) return;

        // Get assigned subjects for this class
        $classSubjects = DB::table('class_subject')
            ->where('class_id', $class->id)
            ->where('is_active', true)
            ->limit(5) // Just first 5 for demo
            ->get();

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $timeSlots = [
            ['start' => '08:00', 'end' => '08:45'],
            ['start' => '08:45', 'end' => '09:30'],
            ['start' => '09:45', 'end' => '10:30'],
            ['start' => '10:30', 'end' => '11:15'],
            ['start' => '11:30', 'end' => '12:15'],
        ];

        $slotIndex = 0;
        foreach ($days as $day) {
            foreach ($timeSlots as $slot) {
                if ($slotIndex < $classSubjects->count()) {
                    $subject = $classSubjects[$slotIndex];
                    
                    Timetable::create([
                        'class_id' => $class->id,
                        'subject_id' => $subject->subject_id,
                        'teacher_id' => $subject->teacher_id,
                        'day_of_week' => $day,
                        'start_time' => $slot['start'],
                        'end_time' => $slot['end'],
                        'room' => 'Room ' . (($slotIndex % 10) + 1),
                        'is_active' => true,
                    ]);
                    
                    $slotIndex++;
                    if ($slotIndex >= $classSubjects->count()) {
                        $slotIndex = 0; // Reset to cycle through subjects
                    }
                }
            }
        }
    }
}