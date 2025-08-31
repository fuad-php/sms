<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding exams...');

        // Get classes and subjects
        $classes = SchoolClass::active()->get();
        $subjects = Subject::all();

        if ($classes->isEmpty() || $subjects->isEmpty()) {
            $this->command->info('No classes or subjects found. Please run ClassSeeder and SubjectSeeder first.');
            return;
        }

        $examTypes = ['Mid Term', 'Final Term', 'Unit Test', 'Quiz'];
        $examNames = [
            'Mathematics' => ['Algebra Test', 'Geometry Quiz', 'Calculus Exam', 'Statistics Test'],
            'Science' => ['Physics Quiz', 'Chemistry Test', 'Biology Exam', 'Lab Test'],
            'English' => ['Grammar Test', 'Literature Quiz', 'Comprehension Test', 'Writing Exam'],
            'History' => ['Ancient History Test', 'Modern History Quiz', 'World History Exam'],
            'Geography' => ['Physical Geography Test', 'Human Geography Quiz', 'Map Reading Test'],
        ];

        $exams = [];

        foreach ($classes as $class) {
            // Get subjects for this class using direct query
            $classSubjectIds = DB::table('class_subject')
                ->where('class_id', $class->id)
                ->where('is_active', true)
                ->pluck('subject_id');
            
            if ($classSubjectIds->isEmpty()) {
                $this->command->info("No subjects found for class {$class->name}. Skipping...");
                continue;
            }
            
            $subjects = Subject::whereIn('id', $classSubjectIds)->get();
            
            foreach ($subjects as $subject) {
                $subjectName = $subject->name;
                
                // Create 2-4 exams per subject per class
                $numExams = rand(2, 4);
                
                for ($i = 0; $i < $numExams; $i++) {
                    $examType = $examTypes[array_rand($examTypes)];
                    
                    // Use subject name directly if not in predefined array
                    $examName = isset($examNames[$subjectName]) && !empty($examNames[$subjectName]) 
                        ? $examNames[$subjectName][array_rand($examNames[$subjectName])] 
                        : "{$subjectName} {$examType}";
                    
                    // Generate exam date (within last 6 months to next 2 months)
                    $examDate = Carbon::now()->addDays(rand(-180, 60));
                    
                    // Generate time (between 9 AM and 3 PM)
                    $startHour = rand(9, 14);
                    $startTime = Carbon::createFromTime($startHour, rand(0, 3) * 15, 0);
                    $endTime = $startTime->copy()->addMinutes(rand(60, 180));
                    
                    // Generate marks (based on class level)
                    $totalMarks = $this->getTotalMarksForClass($class->name);
                    $passingMarks = round($totalMarks * 0.4); // 40% passing marks
                    
                    $exams[] = [
                        'name' => $examName,
                        'description' => "{$examType} for {$subjectName} - {$class->name}",
                        'class_id' => $class->id,
                        'subject_id' => $subject->id,
                        'exam_date' => $examDate->format('Y-m-d'),
                        'start_time' => $startTime->format('H:i:s'),
                        'end_time' => $endTime->format('H:i:s'),
                        'total_marks' => $totalMarks,
                        'passing_marks' => $passingMarks,
                        'exam_type' => $examType,
                        'is_published' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insert exams in chunks
        foreach (array_chunk($exams, 50) as $chunk) {
            Exam::insert($chunk);
        }

        $this->command->info('Created ' . count($exams) . ' exams successfully!');
    }

    /**
     * Get total marks based on class level
     */
    private function getTotalMarksForClass(string $className): int
    {
        if (str_contains($className, '1') || str_contains($className, '2') || str_contains($className, '3')) {
            return 50; // Lower classes
        } elseif (str_contains($className, '4') || str_contains($className, '5') || str_contains($className, '6')) {
            return 75; // Middle classes
        } else {
            return 100; // Higher classes
        }
    }
}
