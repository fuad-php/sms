<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add timing and format settings
        $timingSettings = [
            // Class Timing Settings
            [
                'key' => 'class_start_time',
                'value' => '08:00',
                'type' => 'time',
                'group' => 'timing',
                'label' => 'Class Start Time',
                'description' => 'The time when classes start each day',
                'is_public' => true,
            ],
            [
                'key' => 'class_interval_minutes',
                'value' => '15',
                'type' => 'number',
                'group' => 'timing',
                'label' => 'Interval Time (Minutes)',
                'description' => 'Time interval between classes in minutes',
                'is_public' => true,
            ],
            [
                'key' => 'class_duration_minutes',
                'value' => '45',
                'type' => 'number',
                'group' => 'timing',
                'label' => 'Per Class Duration (Minutes)',
                'description' => 'Duration of each class in minutes',
                'is_public' => true,
            ],
            [
                'key' => 'weekly_offdays',
                'value' => '["Friday"]',
                'type' => 'json',
                'group' => 'timing',
                'label' => 'Weekly Off Days',
                'description' => 'Days of the week when school is closed',
                'is_public' => true,
            ],
            
            // Date and Time Format Settings
            [
                'key' => 'date_format',
                'value' => 'd/m/Y',
                'type' => 'string',
                'group' => 'format',
                'label' => 'Date Format',
                'description' => 'Default date format for the application',
                'is_public' => true,
            ],
            [
                'key' => 'time_format',
                'value' => '12',
                'type' => 'string',
                'group' => 'format',
                'label' => 'Time Format',
                'description' => 'Time format: 12-hour or 24-hour',
                'is_public' => true,
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Dhaka',
                'type' => 'string',
                'group' => 'format',
                'label' => 'Timezone',
                'description' => 'Default timezone for the application',
                'is_public' => true,
            ],
            [
                'key' => 'timezone_offset',
                'value' => '+06:00',
                'type' => 'string',
                'group' => 'format',
                'label' => 'Timezone Offset',
                'description' => 'Timezone offset from UTC',
                'is_public' => true,
            ],
        ];

        foreach ($timingSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $keys = [
            'class_start_time',
            'class_interval_minutes',
            'class_duration_minutes',
            'weekly_offdays',
            'date_format',
            'time_format',
            'timezone',
            'timezone_offset',
        ];

        Setting::whereIn('key', $keys)->delete();
    }
};