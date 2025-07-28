<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ClassSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $teachers = User::where('role', 'teacher')->get();

        // Assign subjects to Grade 9 classes
        $grade9Classes = $classes->where('name', 'Grade 9');
        
        foreach ($grade9Classes as $class) {
            // Core subjects for Grade 9
            $classSubjects = [
                ['subject' => 'Mathematics', 'teacher' => $teachers[0], 'periods' => 6],
                ['subject' => 'English Language Arts', 'teacher' => $teachers[1], 'periods' => 5],
                ['subject' => 'Science', 'teacher' => $teachers[2], 'periods' => 5],
                ['subject' => 'Social Studies', 'teacher' => $teachers[1], 'periods' => 4],
                ['subject' => 'Physical Education', 'teacher' => $teachers[2], 'periods' => 2],
                ['subject' => 'Computer Science', 'teacher' => $teachers[0], 'periods' => 3],
            ];

            foreach ($classSubjects as $assignment) {
                $subject = $subjects->where('name', $assignment['subject'])->first();
                if ($subject) {
                    DB::table('class_subject')->insert([
                        'class_id' => $class->id,
                        'subject_id' => $subject->id,
                        'teacher_id' => $assignment['teacher']->id,
                        'periods_per_week' => $assignment['periods'],
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // Assign subjects to Grade 10 classes
        $grade10Classes = $classes->where('name', 'Grade 10');
        
        foreach ($grade10Classes as $class) {
            $classSubjects = [
                ['subject' => 'Mathematics', 'teacher' => $teachers[0], 'periods' => 6],
                ['subject' => 'English Language Arts', 'teacher' => $teachers[1], 'periods' => 5],
                ['subject' => 'Science', 'teacher' => $teachers[2], 'periods' => 6],
                ['subject' => 'Social Studies', 'teacher' => $teachers[1], 'periods' => 4],
                ['subject' => 'Physical Education', 'teacher' => $teachers[2], 'periods' => 2],
                ['subject' => 'Computer Science', 'teacher' => $teachers[0], 'periods' => 3],
                ['subject' => 'Art', 'teacher' => $teachers[1], 'periods' => 2],
            ];

            foreach ($classSubjects as $assignment) {
                $subject = $subjects->where('name', $assignment['subject'])->first();
                if ($subject) {
                    DB::table('class_subject')->insert([
                        'class_id' => $class->id,
                        'subject_id' => $subject->id,
                        'teacher_id' => $assignment['teacher']->id,
                        'periods_per_week' => $assignment['periods'],
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // Assign subjects to Grade 11 classes
        $grade11Classes = $classes->where('name', 'Grade 11');
        
        foreach ($grade11Classes as $class) {
            if ($class->section === 'Science') {
                $classSubjects = [
                    ['subject' => 'Mathematics', 'teacher' => $teachers[0], 'periods' => 8],
                    ['subject' => 'English Language Arts', 'teacher' => $teachers[1], 'periods' => 4],
                    ['subject' => 'Science', 'teacher' => $teachers[2], 'periods' => 10],
                    ['subject' => 'Computer Science', 'teacher' => $teachers[0], 'periods' => 4],
                    ['subject' => 'Physical Education', 'teacher' => $teachers[2], 'periods' => 2],
                ];
            } else { // Arts stream
                $classSubjects = [
                    ['subject' => 'Mathematics', 'teacher' => $teachers[0], 'periods' => 4],
                    ['subject' => 'English Language Arts', 'teacher' => $teachers[1], 'periods' => 6],
                    ['subject' => 'Social Studies', 'teacher' => $teachers[1], 'periods' => 8],
                    ['subject' => 'Art', 'teacher' => $teachers[1], 'periods' => 6],
                    ['subject' => 'Music', 'teacher' => $teachers[2], 'periods' => 4],
                    ['subject' => 'Physical Education', 'teacher' => $teachers[2], 'periods' => 2],
                ];
            }

            foreach ($classSubjects as $assignment) {
                $subject = $subjects->where('name', $assignment['subject'])->first();
                if ($subject) {
                    DB::table('class_subject')->insert([
                        'class_id' => $class->id,
                        'subject_id' => $subject->id,
                        'teacher_id' => $assignment['teacher']->id,
                        'periods_per_week' => $assignment['periods'],
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}