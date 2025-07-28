<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Mathematics',
                'code' => 'MATH',
                'description' => 'Basic mathematics including algebra, geometry, and calculus',
                'color' => '#3B82F6',
            ],
            [
                'name' => 'English Language Arts',
                'code' => 'ELA',
                'description' => 'Reading, writing, speaking, and listening skills',
                'color' => '#10B981',
            ],
            [
                'name' => 'Science',
                'code' => 'SCI',
                'description' => 'Biology, Chemistry, and Physics fundamentals',
                'color' => '#F59E0B',
            ],
            [
                'name' => 'Social Studies',
                'code' => 'SS',
                'description' => 'History, geography, civics, and economics',
                'color' => '#EF4444',
            ],
            [
                'name' => 'Physical Education',
                'code' => 'PE',
                'description' => 'Physical fitness and health education',
                'color' => '#8B5CF6',
            ],
            [
                'name' => 'Computer Science',
                'code' => 'CS',
                'description' => 'Programming, algorithms, and computer literacy',
                'color' => '#06B6D4',
            ],
            [
                'name' => 'Art',
                'code' => 'ART',
                'description' => 'Visual arts, painting, drawing, and creative expression',
                'color' => '#EC4899',
            ],
            [
                'name' => 'Music',
                'code' => 'MUS',
                'description' => 'Music theory, performance, and appreciation',
                'color' => '#84CC16',
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}