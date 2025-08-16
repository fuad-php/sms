<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SubjectSeeder::class,
            ClassSeeder::class,
            StudentSeeder::class,
            TeacherSeeder::class,
            ClassSubjectSeeder::class,
            TimetableSeeder::class,
            AnnouncementSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}
