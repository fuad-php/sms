<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Timetable;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;

class TimetableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some sample data
        $classes = SchoolClass::take(3)->get();
        $subjects = Subject::take(5)->get();
        $teachers = User::where('role', 'teacher')->take(3)->get();

        if ($classes->isEmpty() || $subjects->isEmpty() || $teachers->isEmpty()) {
            $this->command->info('Skipping timetable seeding - need classes, subjects, and teachers first.');
            return;
        }

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $timeSlots = [
            ['08:00', '08:45'],
            ['08:45', '09:30'],
            ['09:30', '10:15'],
            ['10:15', '11:00'],
            ['11:00', '11:45'],
            ['11:45', '12:30'],
            ['13:15', '14:00'],
            ['14:00', '14:45'],
            ['14:45', '15:30'],
            ['15:30', '16:15'],
        ];

        $timetables = [];

        foreach ($classes as $class) {
            foreach ($days as $day) {
                foreach ($timeSlots as $index => $timeSlot) {
                    // Skip some time slots to create breaks
                    if (in_array($index, [5, 6])) continue; // Lunch break
                    
                    $subject = $subjects->random();
                    $teacher = $teachers->random();
                    
                    $timetables[] = [
                        'class_id' => $class->id,
                        'subject_id' => $subject->id,
                        'teacher_id' => $teacher->id,
                        'day_of_week' => $day,
                        'start_time' => $timeSlot[0],
                        'end_time' => $timeSlot[1],
                        'room' => 'Room ' . rand(1, 10),
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($timetables, 100) as $chunk) {
            Timetable::insert($chunk);
        }

        $this->command->info('Timetable data seeded successfully!');
    }
}