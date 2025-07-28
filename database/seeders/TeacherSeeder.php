<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\User;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacherUsers = User::where('role', 'teacher')->get();

        $teachersData = [
            [
                'user_id' => $teacherUsers[0]->id,
                'employee_id' => 'EMP001',
                'qualification' => 'M.Sc. Mathematics, B.Ed.',
                'specialization' => 'Mathematics, Statistics',
                'salary' => 50000.00,
                'joining_date' => '2020-08-15',
                'experience' => '8 years teaching experience in secondary education',
            ],
            [
                'user_id' => $teacherUsers[1]->id,
                'employee_id' => 'EMP002',
                'qualification' => 'M.A. English Literature, B.Ed.',
                'specialization' => 'English Language Arts, Creative Writing',
                'salary' => 48000.00,
                'joining_date' => '2019-07-20',
                'experience' => '6 years experience in language education',
            ],
            [
                'user_id' => $teacherUsers[2]->id,
                'employee_id' => 'EMP003',
                'qualification' => 'M.Sc. Chemistry, B.Ed.',
                'specialization' => 'Chemistry, General Science',
                'salary' => 52000.00,
                'joining_date' => '2018-09-10',
                'experience' => '10 years experience in science education',
            ],
        ];

        foreach ($teachersData as $teacherData) {
            Teacher::create($teacherData);
        }
    }
}