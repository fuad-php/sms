<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update Admin User
        User::updateOrCreate(
            ['email' => 'admin@school.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '+1-234-567-8900',
                'address' => '123 School Admin Street',
                'date_of_birth' => '1980-01-15',
                'gender' => 'other',
                'is_active' => true,
            ]
        );

        // Create Sample Teachers
        $teachers = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@school.com',
                'password' => Hash::make('teacher123'),
                'role' => 'teacher',
                'phone' => '+1-234-567-8901',
                'address' => '456 Teacher Lane',
                'date_of_birth' => '1985-03-22',
                'gender' => 'male',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@school.com',
                'password' => Hash::make('teacher123'),
                'role' => 'teacher',
                'phone' => '+1-234-567-8902',
                'address' => '789 Education Blvd',
                'date_of_birth' => '1988-07-10',
                'gender' => 'female',
            ],
            [
                'name' => 'Michael Davis',
                'email' => 'michael.davis@school.com',
                'password' => Hash::make('teacher123'),
                'role' => 'teacher',
                'phone' => '+1-234-567-8903',
                'address' => '321 Learning Ave',
                'date_of_birth' => '1982-11-05',
                'gender' => 'male',
            ],
        ];

        foreach ($teachers as $teacher) {
            $email = $teacher['email'];
            $data = $teacher;
            unset($data['email']);
            User::updateOrCreate(['email' => $email], $data);
        }

        // Create Sample Students
        $students = [
            [
                'name' => 'Alice Wilson',
                'email' => 'alice.wilson@student.school.com',
                'password' => Hash::make('student123'),
                'role' => 'student',
                'phone' => '+1-234-567-8910',
                'address' => '111 Student Street',
                'date_of_birth' => '2008-09-15',
                'gender' => 'female',
            ],
            [
                'name' => 'Bob Brown',
                'email' => 'bob.brown@student.school.com',
                'password' => Hash::make('student123'),
                'role' => 'student',
                'phone' => '+1-234-567-8911',
                'address' => '222 Student Avenue',
                'date_of_birth' => '2008-12-03',
                'gender' => 'male',
            ],
            [
                'name' => 'Charlie Miller',
                'email' => 'charlie.miller@student.school.com',
                'password' => Hash::make('student123'),
                'role' => 'student',
                'phone' => '+1-234-567-8912',
                'address' => '333 Learning Lane',
                'date_of_birth' => '2009-01-20',
                'gender' => 'male',
            ],
            [
                'name' => 'Diana Garcia',
                'email' => 'diana.garcia@student.school.com',
                'password' => Hash::make('student123'),
                'role' => 'student',
                'phone' => '+1-234-567-8913',
                'address' => '444 Study Road',
                'date_of_birth' => '2008-06-12',
                'gender' => 'female',
            ],
        ];

        foreach ($students as $student) {
            $email = $student['email'];
            $data = $student;
            unset($data['email']);
            User::updateOrCreate(['email' => $email], $data);
        }

        // Create Sample Parents
        $parents = [
            [
                'name' => 'Robert Wilson',
                'email' => 'robert.wilson@parent.school.com',
                'password' => Hash::make('parent123'),
                'role' => 'parent',
                'phone' => '+1-234-567-8920',
                'address' => '111 Student Street',
                'date_of_birth' => '1975-05-10',
                'gender' => 'male',
            ],
            [
                'name' => 'Lisa Brown',
                'email' => 'lisa.brown@parent.school.com',
                'password' => Hash::make('parent123'),
                'role' => 'parent',
                'phone' => '+1-234-567-8921',
                'address' => '222 Student Avenue',
                'date_of_birth' => '1978-08-25',
                'gender' => 'female',
            ],
        ];

        foreach ($parents as $parent) {
            $email = $parent['email'];
            $data = $parent;
            unset($data['email']);
            User::updateOrCreate(['email' => $email], $data);
        }

        // Create Sample Staff
        $staffUsers = [
            [
                'name' => 'Emma Thompson',
                'email' => 'emma.thompson@school.com',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'phone' => '+1-234-567-8930',
                'address' => '555 Administration Way',
                'date_of_birth' => '1990-04-18',
                'gender' => 'female',
            ],
        ];

        foreach ($staffUsers as $staff) {
            $email = $staff['email'];
            $data = $staff;
            unset($data['email']);
            User::updateOrCreate(['email' => $email], $data);
        }
    }
}