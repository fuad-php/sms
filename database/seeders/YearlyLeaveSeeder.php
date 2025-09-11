<?php

namespace Database\Seeders;

use App\Models\YearlyLeave;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class YearlyLeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = now()->year;
        
        $yearlyLeaves = [
            // National Holidays
            [
                'title' => 'Independence Day',
                'description' => 'National Independence Day celebration',
                'start_date' => Carbon::create($currentYear, 3, 26)->toDateString(),
                'end_date' => Carbon::create($currentYear, 3, 26)->toDateString(),
                'year' => $currentYear,
                'type' => 'holiday',
                'is_active' => true,
            ],
            [
                'title' => 'Victory Day',
                'description' => 'Victory Day celebration',
                'start_date' => Carbon::create($currentYear, 12, 16)->toDateString(),
                'end_date' => Carbon::create($currentYear, 12, 16)->toDateString(),
                'year' => $currentYear,
                'type' => 'holiday',
                'is_active' => true,
            ],
            [
                'title' => 'Language Martyrs Day',
                'description' => 'International Mother Language Day',
                'start_date' => Carbon::create($currentYear, 2, 21)->toDateString(),
                'end_date' => Carbon::create($currentYear, 2, 21)->toDateString(),
                'year' => $currentYear,
                'type' => 'holiday',
                'is_active' => true,
            ],
            
            // Religious Holidays
            [
                'title' => 'Eid-ul-Fitr',
                'description' => 'End of Ramadan celebration',
                'start_date' => Carbon::create($currentYear, 4, 10)->toDateString(),
                'end_date' => Carbon::create($currentYear, 4, 12)->toDateString(),
                'year' => $currentYear,
                'type' => 'holiday',
                'is_active' => true,
            ],
            [
                'title' => 'Eid-ul-Azha',
                'description' => 'Festival of Sacrifice',
                'start_date' => Carbon::create($currentYear, 6, 16)->toDateString(),
                'end_date' => Carbon::create($currentYear, 6, 18)->toDateString(),
                'year' => $currentYear,
                'type' => 'holiday',
                'is_active' => true,
            ],
            [
                'title' => 'Durga Puja',
                'description' => 'Hindu festival celebration',
                'start_date' => Carbon::create($currentYear, 10, 12)->toDateString(),
                'end_date' => Carbon::create($currentYear, 10, 16)->toDateString(),
                'year' => $currentYear,
                'type' => 'holiday',
                'is_active' => true,
            ],
            [
                'title' => 'Christmas Day',
                'description' => 'Christmas celebration',
                'start_date' => Carbon::create($currentYear, 12, 25)->toDateString(),
                'end_date' => Carbon::create($currentYear, 12, 25)->toDateString(),
                'year' => $currentYear,
                'type' => 'holiday',
                'is_active' => true,
            ],
            
            // Academic Breaks
            [
                'title' => 'Summer Vacation',
                'description' => 'Annual summer break for students and teachers',
                'start_date' => Carbon::create($currentYear, 5, 15)->toDateString(),
                'end_date' => Carbon::create($currentYear, 6, 30)->toDateString(),
                'year' => $currentYear,
                'type' => 'vacation',
                'is_active' => true,
            ],
            [
                'title' => 'Winter Vacation',
                'description' => 'Winter break for students and teachers',
                'start_date' => Carbon::create($currentYear, 12, 20)->toDateString(),
                'end_date' => Carbon::create($currentYear + 1, 1, 5)->toDateString(),
                'year' => $currentYear,
                'type' => 'vacation',
                'is_active' => true,
            ],
            
            // Exam Periods
            [
                'title' => 'Annual Examination',
                'description' => 'Year-end examination period',
                'start_date' => Carbon::create($currentYear, 11, 1)->toDateString(),
                'end_date' => Carbon::create($currentYear, 11, 30)->toDateString(),
                'year' => $currentYear,
                'type' => 'exam_period',
                'is_active' => true,
            ],
            [
                'title' => 'Mid-term Examination',
                'description' => 'Mid-year examination period',
                'start_date' => Carbon::create($currentYear, 7, 15)->toDateString(),
                'end_date' => Carbon::create($currentYear, 7, 25)->toDateString(),
                'year' => $currentYear,
                'type' => 'exam_period',
                'is_active' => true,
            ],
            
            // School Events
            [
                'title' => 'Annual Sports Day',
                'description' => 'School sports competition and activities',
                'start_date' => Carbon::create($currentYear, 2, 10)->toDateString(),
                'end_date' => Carbon::create($currentYear, 2, 10)->toDateString(),
                'year' => $currentYear,
                'type' => 'other',
                'is_active' => true,
            ],
            [
                'title' => 'Cultural Program',
                'description' => 'Annual cultural program and celebration',
                'start_date' => Carbon::create($currentYear, 3, 15)->toDateString(),
                'end_date' => Carbon::create($currentYear, 3, 15)->toDateString(),
                'year' => $currentYear,
                'type' => 'other',
                'is_active' => true,
            ],
        ];

        foreach ($yearlyLeaves as $leave) {
            YearlyLeave::create($leave);
        }
    }
}
