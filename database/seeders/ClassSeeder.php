<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolClass;
use App\Models\User;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = User::where('role', 'teacher')->get();
        
        $classes = [
            [
                'name' => 'Grade 9',
                'section' => 'A',
                'description' => 'Grade 9 Section A - Morning batch',
                'class_teacher_id' => $teachers->first()->id,
                'capacity' => 30,
            ],
            [
                'name' => 'Grade 9',
                'section' => 'B',
                'description' => 'Grade 9 Section B - Afternoon batch',
                'class_teacher_id' => $teachers->skip(1)->first()->id,
                'capacity' => 30,
            ],
            [
                'name' => 'Grade 10',
                'section' => 'A',
                'description' => 'Grade 10 Section A - Morning batch',
                'class_teacher_id' => $teachers->skip(2)->first()->id,
                'capacity' => 25,
            ],
            [
                'name' => 'Grade 10',
                'section' => 'B',
                'description' => 'Grade 10 Section B - Afternoon batch',
                'class_teacher_id' => $teachers->first()->id,
                'capacity' => 25,
            ],
            [
                'name' => 'Grade 11',
                'section' => 'Science',
                'description' => 'Grade 11 Science Stream',
                'class_teacher_id' => $teachers->skip(1)->first()->id,
                'capacity' => 20,
            ],
            [
                'name' => 'Grade 11',
                'section' => 'Arts',
                'description' => 'Grade 11 Arts Stream',
                'class_teacher_id' => $teachers->skip(2)->first()->id,
                'capacity' => 20,
            ],
        ];

        foreach ($classes as $class) {
            SchoolClass::create($class);
        }
    }
}