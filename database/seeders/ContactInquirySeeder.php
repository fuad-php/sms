<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactInquiry;
use Carbon\Carbon;

class ContactInquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Admissions',
            'Academic',
            'Administration',
            'Student Services',
            'IT Support',
            'General Inquiry'
        ];

        $sampleInquiries = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'phone' => '+1-555-0123',
                'subject' => 'Admission Inquiry for Grade 10',
                'message' => 'I would like to know more about the admission process for Grade 10. What are the requirements and when does the application process begin?',
                'department' => 'Admissions',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'is_read' => false,
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@email.com',
                'phone' => '+1-555-0456',
                'subject' => 'Curriculum Information Request',
                'message' => 'Could you please provide information about the science curriculum for middle school students? I am particularly interested in the laboratory facilities.',
                'department' => 'Academic',
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'is_read' => true,
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.brown@company.com',
                'phone' => '+1-555-0789',
                'subject' => 'Partnership Opportunity',
                'message' => 'I represent a local business and would like to discuss potential partnership opportunities with your school for student internships and career development programs.',
                'department' => 'Administration',
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
                'is_read' => false,
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@parent.com',
                'phone' => '+1-555-0321',
                'subject' => 'Student Support Services',
                'message' => 'My child is experiencing some academic challenges and I would like to know what support services are available. Are there tutoring programs or counseling services?',
                'department' => 'Student Services',
                'ip_address' => '192.168.1.103',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15',
                'is_read' => true,
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@tech.com',
                'phone' => '+1-555-0654',
                'subject' => 'IT Support Request',
                'message' => 'I am having trouble accessing the parent portal. The login page is not loading properly and I cannot reset my password. Please help.',
                'department' => 'IT Support',
                'ip_address' => '192.168.1.104',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'is_read' => false,
                'created_at' => Carbon::now()->subHours(6),
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@community.com',
                'phone' => null,
                'subject' => 'Community Event Participation',
                'message' => 'I am organizing a community health fair and would like to invite your school to participate. This could be a great opportunity for students to learn about health and wellness.',
                'department' => 'General Inquiry',
                'ip_address' => '192.168.1.105',
                'user_agent' => 'Mozilla/5.0 (iPad; CPU OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15',
                'is_read' => true,
                'created_at' => Carbon::now()->subDays(7),
            ],
            [
                'name' => 'Robert Taylor',
                'email' => 'robert.taylor@student.com',
                'phone' => '+1-555-0987',
                'subject' => 'Extracurricular Activities',
                'message' => 'I am interested in joining the robotics club and would like to know the meeting schedule and requirements. Do I need any prior experience?',
                'department' => 'Student Services',
                'ip_address' => '192.168.1.106',
                'user_agent' => 'Mozilla/5.0 (Android 11; Mobile; rv:91.0) Gecko/91.0',
                'is_read' => false,
                'created_at' => Carbon::now()->subHours(12),
            ],
            [
                'name' => 'Jennifer Martinez',
                'email' => 'jennifer.martinez@parent.com',
                'phone' => '+1-555-0124',
                'subject' => 'Transportation Services',
                'message' => 'I would like to know about the school bus routes and schedules. Is there transportation available for students living in the downtown area?',
                'department' => 'General Inquiry',
                'ip_address' => '192.168.1.107',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'is_read' => true,
                'created_at' => Carbon::now()->subDays(4),
            ],
        ];

        foreach ($sampleInquiries as $inquiry) {
            ContactInquiry::create($inquiry);
        }

        $this->command->info('Contact inquiries seeded successfully!');
    }
}
