<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class ExamResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing results
        DB::table('exam_results')->truncate();

        // Get all exams
        $exams = Exam::with(['class', 'subject'])->get();
        
        if ($exams->isEmpty()) {
            $this->command->info('No exams found. Please run ExamSeeder first.');
            return;
        }

        $this->command->info('Seeding exam results...');

        foreach ($exams as $exam) {
            // Get all students in the class
            $students = Student::where('class_id', $exam->class_id)->get();
            
            if ($students->isEmpty()) {
                $this->command->info("No students found for class {$exam->class->name}. Skipping exam {$exam->name}.");
                continue;
            }

            $results = [];
            
            foreach ($students as $student) {
                // Randomly decide if student is absent (10% chance)
                $isAbsent = rand(1, 10) === 1;
                
                if ($isAbsent) {
                    $results[] = [
                        'exam_id' => $exam->id,
                        'student_id' => $student->id,
                        'marks_obtained' => null,
                        'grade' => null,
                        'remarks' => 'Absent',
                        'is_absent' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    // Generate random marks (between 60% and 100% of total marks)
                    $minMarks = round($exam->total_marks * 0.6);
                    $maxMarks = $exam->total_marks;
                    $marksObtained = rand($minMarks, $maxMarks);
                    
                    // Calculate grade based on percentage
                    $percentage = ($marksObtained / $exam->total_marks) * 100;
                    $grade = $this->calculateGrade($percentage);
                    
                    // Generate random remarks
                    $remarks = $this->getRandomRemarks($percentage);
                    
                    $results[] = [
                        'exam_id' => $exam->id,
                        'student_id' => $student->id,
                        'marks_obtained' => $marksObtained,
                        'grade' => $grade,
                        'remarks' => $remarks,
                        'is_absent' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            // Insert results in chunks to avoid memory issues
            foreach (array_chunk($results, 100) as $chunk) {
                ExamResult::insert($chunk);
            }
            
            $this->command->info("Created " . count($results) . " results for exam: {$exam->name}");
        }

        $this->command->info('Exam results seeding completed!');
    }

    /**
     * Calculate grade based on percentage
     */
    private function calculateGrade(float $percentage): string
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 33) return 'D';
        return 'F';
    }

    /**
     * Get random remarks based on performance
     */
    private function getRandomRemarks(float $percentage): string
    {
        if ($percentage >= 90) {
            $remarks = [
                'Excellent performance!',
                'Outstanding work!',
                'Exceptional achievement!',
                'Top performer!',
                'Brilliant work!'
            ];
        } elseif ($percentage >= 80) {
            $remarks = [
                'Very good performance',
                'Well done!',
                'Good work!',
                'Keep it up!',
                'Satisfactory performance'
            ];
        } elseif ($percentage >= 70) {
            $remarks = [
                'Good effort',
                'Satisfactory work',
                'Can improve further',
                'Decent performance',
                'Room for improvement'
            ];
        } elseif ($percentage >= 60) {
            $remarks = [
                'Average performance',
                'Needs more practice',
                'Can do better',
                'Requires attention',
                'Work harder next time'
            ];
        } else {
            $remarks = [
                'Needs improvement',
                'Requires extra help',
                'Below average',
                'Needs more study',
                'Focus on weak areas'
            ];
        }
        
        return $remarks[array_rand($remarks)];
    }
}
