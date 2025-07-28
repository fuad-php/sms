<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentUsers = User::where('role', 'student')->get();
        $classes = SchoolClass::all();

        $studentsData = [
            [
                'user_id' => $studentUsers[0]->id,
                'student_id' => 'STU001',
                'class_id' => $classes[0]->id, // Grade 9 A
                'admission_date' => '2024-09-01',
                'roll_number' => '001',
                'blood_group' => 'A+',
                'medical_info' => 'No known allergies',
                'guardian_name' => 'Robert Wilson',
                'guardian_phone' => '+1-234-567-8920',
                'guardian_email' => 'robert.wilson@parent.school.com',
                'guardian_address' => '111 Student Street',
                'emergency_contact' => '+1-234-567-8921',
            ],
            [
                'user_id' => $studentUsers[1]->id,
                'student_id' => 'STU002',
                'class_id' => $classes[0]->id, // Grade 9 A
                'admission_date' => '2024-09-01',
                'roll_number' => '002',
                'blood_group' => 'B+',
                'medical_info' => 'Mild asthma',
                'guardian_name' => 'Lisa Brown',
                'guardian_phone' => '+1-234-567-8921',
                'guardian_email' => 'lisa.brown@parent.school.com',
                'guardian_address' => '222 Student Avenue',
                'emergency_contact' => '+1-234-567-8922',
            ],
            [
                'user_id' => $studentUsers[2]->id,
                'student_id' => 'STU003',
                'class_id' => $classes[1]->id, // Grade 9 B
                'admission_date' => '2024-09-01',
                'roll_number' => '001',
                'blood_group' => 'O+',
                'medical_info' => 'No known medical conditions',
                'guardian_name' => 'James Miller',
                'guardian_phone' => '+1-234-567-8930',
                'guardian_email' => 'james.miller@example.com',
                'guardian_address' => '333 Learning Lane',
                'emergency_contact' => '+1-234-567-8931',
            ],
            [
                'user_id' => $studentUsers[3]->id,
                'student_id' => 'STU004',
                'class_id' => $classes[2]->id, // Grade 10 A
                'admission_date' => '2023-09-01',
                'roll_number' => '001',
                'blood_group' => 'AB+',
                'medical_info' => 'Wears glasses',
                'guardian_name' => 'Maria Garcia',
                'guardian_phone' => '+1-234-567-8940',
                'guardian_email' => 'maria.garcia@example.com',
                'guardian_address' => '444 Study Road',
                'emergency_contact' => '+1-234-567-8941',
            ],
        ];

        foreach ($studentsData as $studentData) {
            Student::create($studentData);
        }
    }
}