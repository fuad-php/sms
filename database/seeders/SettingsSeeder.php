<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            // School Information
            [
                'key' => 'school_name',
                'value' => 'Excellence School',
                'type' => 'string',
                'group' => 'school_info',
                'label' => 'School Name',
                'description' => 'The official name of the school',
                'is_public' => true,
            ],
            [
                'key' => 'school_address',
                'value' => '123 Education Street, City, State 12345',
                'type' => 'text',
                'group' => 'school_info',
                'label' => 'School Address',
                'description' => 'Complete address of the school',
                'is_public' => true,
            ],
            [
                'key' => 'school_phone',
                'value' => '+1-234-567-8900',
                'type' => 'string',
                'group' => 'school_info',
                'label' => 'School Phone',
                'description' => 'Main contact phone number',
                'is_public' => true,
            ],
            [
                'key' => 'school_email',
                'value' => 'info@excellenceschool.com',
                'type' => 'string',
                'group' => 'school_info',
                'label' => 'School Email',
                'description' => 'Main contact email address',
                'is_public' => true,
            ],
            [
                'key' => 'school_website',
                'value' => 'https://www.excellenceschool.com',
                'type' => 'string',
                'group' => 'school_info',
                'label' => 'School Website',
                'description' => 'Official school website URL',
                'is_public' => true,
            ],
            [
                'key' => 'school_principal',
                'value' => 'Dr. John Smith',
                'type' => 'string',
                'group' => 'school_info',
                'label' => 'School Principal',
                'description' => 'Name of the school principal',
                'is_public' => true,
            ],
            [
                'key' => 'school_established',
                'value' => '1995',
                'type' => 'number',
                'group' => 'school_info',
                'label' => 'Established Year',
                'description' => 'Year when the school was established',
                'is_public' => true,
            ],
            [
                'key' => 'school_motto',
                'value' => 'Excellence in Education',
                'type' => 'string',
                'group' => 'school_info',
                'label' => 'School Motto',
                'description' => 'School motto or tagline',
                'is_public' => true,
            ],
            [
                'key' => 'school_logo',
                'value' => '/images/logo.png',
                'type' => 'string',
                'group' => 'school_info',
                'label' => 'School Logo',
                'description' => 'Path to school logo image',
                'is_public' => true,
            ],

            // Academic Settings
            [
                'key' => 'academic_year',
                'value' => '2024-2025',
                'type' => 'string',
                'group' => 'academic',
                'label' => 'Current Academic Year',
                'description' => 'Current academic year',
                'is_public' => true,
            ],
            [
                'key' => 'semester',
                'value' => '1',
                'type' => 'number',
                'group' => 'academic',
                'label' => 'Current Semester',
                'description' => 'Current semester (1 or 2)',
                'is_public' => true,
            ],
            [
                'key' => 'max_attendance_percentage',
                'value' => '75',
                'type' => 'number',
                'group' => 'academic',
                'label' => 'Minimum Attendance Percentage',
                'description' => 'Minimum attendance percentage required for students',
                'is_public' => false,
            ],
            [
                'key' => 'passing_percentage',
                'value' => '40',
                'type' => 'number',
                'group' => 'academic',
                'label' => 'Passing Percentage',
                'description' => 'Minimum percentage required to pass exams',
                'is_public' => false,
            ],
            [
                'key' => 'max_students_per_class',
                'value' => '30',
                'type' => 'number',
                'group' => 'academic',
                'label' => 'Maximum Students per Class',
                'description' => 'Maximum number of students allowed per class',
                'is_public' => false,
            ],

            // System Settings
            [
                'key' => 'system_maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Maintenance Mode',
                'description' => 'Enable maintenance mode for the system',
                'is_public' => false,
            ],
            [
                'key' => 'email_notifications',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Email Notifications',
                'description' => 'Enable email notifications',
                'is_public' => false,
            ],
            [
                'key' => 'sms_notifications',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'SMS Notifications',
                'description' => 'Enable SMS notifications',
                'is_public' => false,
            ],
            [
                'key' => 'session_timeout',
                'value' => '30',
                'type' => 'number',
                'group' => 'system',
                'label' => 'Session Timeout (minutes)',
                'description' => 'User session timeout in minutes',
                'is_public' => false,
            ],
            [
                'key' => 'file_upload_max_size',
                'value' => '5',
                'type' => 'number',
                'group' => 'system',
                'label' => 'Maximum File Upload Size (MB)',
                'description' => 'Maximum file size allowed for uploads in megabytes',
                'is_public' => false,
            ],
            [
                'key' => 'allowed_file_types',
                'value' => 'jpg,jpeg,png,pdf,doc,docx',
                'type' => 'string',
                'group' => 'system',
                'label' => 'Allowed File Types',
                'description' => 'Comma-separated list of allowed file extensions',
                'is_public' => false,
            ],
        ];

        foreach ($defaultSettings as $settingData) {
            Setting::updateOrCreate(
                ['key' => $settingData['key']],
                $settingData
            );
        }
    }
}
