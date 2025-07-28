<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;
use App\Models\SchoolClass;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $teacher = User::where('role', 'teacher')->first();
        $class = SchoolClass::first();

        $announcements = [
            [
                'title' => 'Welcome to New Academic Year 2024-25',
                'content' => 'We welcome all students, parents, and staff to the new academic year. Classes will begin on September 1st, 2024. Please ensure all admission formalities are completed before the start date.',
                'created_by' => $admin->id,
                'target_audience' => 'all',
                'priority' => 'high',
                'publish_date' => now()->subDays(7),
                'is_published' => true,
            ],
            [
                'title' => 'Parent-Teacher Meeting Schedule',
                'content' => 'The monthly parent-teacher meeting is scheduled for September 15th, 2024, from 2:00 PM to 5:00 PM. Parents are requested to meet their child\'s class teacher and subject teachers.',
                'created_by' => $admin->id,
                'target_audience' => 'parents',
                'priority' => 'medium',
                'publish_date' => now()->subDays(3),
                'is_published' => true,
            ],
            [
                'title' => 'Mid-term Examination Notice',
                'content' => 'Mid-term examinations will be conducted from October 15th to October 25th, 2024. Detailed time table will be shared soon. Students are advised to prepare well.',
                'created_by' => $teacher->id,
                'target_audience' => 'students',
                'class_id' => $class->id,
                'priority' => 'high',
                'publish_date' => now()->subDays(1),
                'is_published' => true,
            ],
            [
                'title' => 'Sports Day Announcement',
                'content' => 'Annual Sports Day will be held on November 5th, 2024. Registration for various events is now open. Contact the Physical Education department for more details.',
                'created_by' => $admin->id,
                'target_audience' => 'all',
                'priority' => 'medium',
                'publish_date' => now(),
                'is_published' => true,
            ],
            [
                'title' => 'Library New Books Collection',
                'content' => 'New collection of books has arrived in the school library. Students can check out the latest additions including fiction, non-fiction, and reference materials.',
                'created_by' => $admin->id,
                'target_audience' => 'students',
                'priority' => 'low',
                'publish_date' => now(),
                'is_published' => true,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}