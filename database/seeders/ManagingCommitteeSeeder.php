<?php

namespace Database\Seeders;

use App\Models\ManagingCommittee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManagingCommitteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $committees = [
            [
                'name' => 'Dr. Mohammad Ali',
                'designation' => 'Principal',
                'position' => 'Chairman',
                'bio' => 'Dr. Mohammad Ali has been serving as the Principal of our school for over 10 years. He holds a Ph.D. in Education and has extensive experience in educational administration.',
                'email' => 'principal@school.edu',
                'phone' => '+880-1234-567890',
                'sort_order' => 1,
                'is_active' => true,
                'is_featured' => true,
                'term_start' => '2020-01-01',
                'term_end' => '2025-12-31',
            ],
            [
                'name' => 'Mrs. Fatima Begum',
                'designation' => 'Vice Principal',
                'position' => 'Vice-Chairman',
                'bio' => 'Mrs. Fatima Begum brings 15 years of teaching experience and has been instrumental in curriculum development and student welfare programs.',
                'email' => 'vice.principal@school.edu',
                'phone' => '+880-1234-567891',
                'sort_order' => 2,
                'is_active' => true,
                'is_featured' => true,
                'term_start' => '2020-01-01',
                'term_end' => '2025-12-31',
            ],
            [
                'name' => 'Mr. Abdul Rahman',
                'designation' => 'Senior Teacher',
                'position' => 'Secretary',
                'bio' => 'Mr. Abdul Rahman has been teaching Mathematics for 12 years and serves as the committee secretary, handling administrative matters efficiently.',
                'email' => 'secretary@school.edu',
                'phone' => '+880-1234-567892',
                'sort_order' => 3,
                'is_active' => true,
                'is_featured' => false,
                'term_start' => '2022-01-01',
                'term_end' => '2024-12-31',
            ],
            [
                'name' => 'Mrs. Nasreen Akhtar',
                'designation' => 'Accountant',
                'position' => 'Treasurer',
                'bio' => 'Mrs. Nasreen Akhtar manages the school finances with 8 years of experience in educational accounting and budget management.',
                'email' => 'treasurer@school.edu',
                'phone' => '+880-1234-567893',
                'sort_order' => 4,
                'is_active' => true,
                'is_featured' => false,
                'term_start' => '2021-01-01',
                'term_end' => '2024-12-31',
            ],
            [
                'name' => 'Mr. Karim Uddin',
                'designation' => 'Parent Representative',
                'position' => 'Member',
                'bio' => 'Mr. Karim Uddin represents the parent community and brings valuable insights from a parent\'s perspective to school management.',
                'email' => 'karim.uddin@email.com',
                'phone' => '+880-1234-567894',
                'sort_order' => 5,
                'is_active' => true,
                'is_featured' => false,
                'term_start' => '2023-01-01',
                'term_end' => '2025-12-31',
            ],
            [
                'name' => 'Mrs. Roksana Parvin',
                'designation' => 'Teacher Representative',
                'position' => 'Member',
                'bio' => 'Mrs. Roksana Parvin represents the teaching staff and ensures that teacher concerns are addressed in committee decisions.',
                'email' => 'roksana.parvin@school.edu',
                'phone' => '+880-1234-567895',
                'sort_order' => 6,
                'is_active' => true,
                'is_featured' => false,
                'term_start' => '2023-01-01',
                'term_end' => '2025-12-31',
            ],
            [
                'name' => 'Dr. Ahmed Hassan',
                'designation' => 'Education Consultant',
                'position' => 'Advisor',
                'bio' => 'Dr. Ahmed Hassan provides expert advice on educational policies and modern teaching methodologies to enhance school performance.',
                'email' => 'ahmed.hassan@consultant.com',
                'phone' => '+880-1234-567896',
                'sort_order' => 7,
                'is_active' => true,
                'is_featured' => true,
                'term_start' => '2022-01-01',
                'term_end' => '2026-12-31',
            ],
            [
                'name' => 'Mrs. Salma Khatun',
                'designation' => 'Community Leader',
                'position' => 'Member',
                'bio' => 'Mrs. Salma Khatun represents the local community and helps maintain strong relationships between the school and community.',
                'email' => 'salma.khatun@community.org',
                'phone' => '+880-1234-567897',
                'sort_order' => 8,
                'is_active' => true,
                'is_featured' => false,
                'term_start' => '2021-01-01',
                'term_end' => '2024-12-31',
            ],
        ];

        foreach ($committees as $committee) {
            ManagingCommittee::create($committee);
        }
    }
}
