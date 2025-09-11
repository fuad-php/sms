<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingsHelper
{
    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        return Setting::getValue($key, $default);
    }

    /**
     * Get a localized setting value by key with fallbacks
     * Tries key_{locale}, then key, then default
     */
    public static function getLocalized(string $key, $default = null, ?string $locale = null)
    {
        $useLocale = $locale ?: app()->getLocale();

        // Try exact key for locale: e.g., home_about_title_en
        $localizedKey = $key . '_' . $useLocale;
        $value = self::get($localizedKey);
        if (!is_null($value) && $value !== '') {
            return $value;
        }

        // Fallback to base key
        $baseValue = self::get($key);
        if (!is_null($baseValue) && $baseValue !== '') {
            return $baseValue;
        }

        return $default;
    }

    /**
     * Set a setting value by key
     */
    public static function set($key, $value)
    {
        return Setting::setValue($key, $value);
    }

    /**
     * Get school name (multilingual)
     */
    public static function getSchoolName()
    {
        $locale = app()->getLocale();
        $schoolNameKey = "school_name_{$locale}";
        
        // Try to get localized school name first
        $localizedName = self::get($schoolNameKey);
        if ($localizedName) {
            return $localizedName;
        }
        
        // Fallback to default school_name setting
        return self::get('school_name', 'School Management System');
    }

    /**
     * Get school name for specific locale
     */
    public static function getSchoolNameForLocale($locale)
    {
        $schoolNameKey = "school_name_{$locale}";
        return self::get($schoolNameKey, self::get('school_name', 'School Management System'));
    }

    /**
     * Set school name for specific locale
     */
    public static function setSchoolNameForLocale($locale, $name)
    {
        $schoolNameKey = "school_name_{$locale}";
        return self::set($schoolNameKey, $name);
    }

    /**
     * Get school address
     */
    public static function getSchoolAddress()
    {
        return self::get('school_address', '');
    }

    /**
     * Get school phone
     */
    public static function getSchoolPhone()
    {
        return self::get('school_phone', '');
    }

    /**
     * Get school email
     */
    public static function getSchoolEmail()
    {
        return self::get('school_email', '');
    }

    /**
     * Get school website
     */
    public static function getSchoolWebsite()
    {
        return self::get('school_website', '');
    }

    /**
     * Get school principal
     */
    public static function getSchoolPrincipal()
    {
        return self::get('school_principal', '');
    }

    /**
     * Get school established year
     */
    public static function getSchoolEstablished()
    {
        return self::get('school_established', '');
    }

    /**
     * Get school motto
     */
    public static function getSchoolMotto()
    {
        return self::get('school_motto', '');
    }

    /**
     * Get school logo
     */
    public static function getSchoolLogo()
    {
        return self::get('school_logo', '');
    }

    /**
     * Get school logo URL (with proper asset path)
     */
    public static function getSchoolLogoUrl()
    {
        $logo = self::getSchoolLogo();
        if (empty($logo)) {
            return null;
        }
        
        // If it's already a full URL, return as is
        if (filter_var($logo, FILTER_VALIDATE_URL)) {
            return $logo;
        }
        
        // If it starts with storage/, it's a stored file
        if (str_starts_with($logo, 'storage/')) {
            return asset($logo);
        }
        
        // Otherwise, treat it as a path from public directory
        return asset($logo);
    }

    /**
     * Check if school has a custom logo
     */
    public static function hasSchoolLogo()
    {
        return !empty(self::getSchoolLogo());
    }

    /**
     * Get current academic year
     */
    public static function getAcademicYear()
    {
        return self::get('academic_year', date('Y') . '-' . (date('Y') + 1));
    }

    /**
     * Get current semester
     */
    public static function getCurrentSemester()
    {
        return self::get('semester', 1);
    }

    /**
     * Get minimum attendance percentage
     */
    public static function getMinAttendancePercentage()
    {
        return self::get('max_attendance_percentage', 75);
    }

    /**
     * Get passing percentage
     */
    public static function getPassingPercentage()
    {
        return self::get('passing_percentage', 40);
    }

    /**
     * Get maximum students per class
     */
    public static function getMaxStudentsPerClass()
    {
        return self::get('max_students_per_class', 30);
    }

    /**
     * Check if maintenance mode is enabled
     */
    public static function isMaintenanceMode()
    {
        return self::get('system_maintenance_mode', false);
    }

    /**
     * Check if email notifications are enabled
     */
    public static function isEmailNotificationsEnabled()
    {
        return self::get('email_notifications', true);
    }

    /**
     * Check if SMS notifications are enabled
     */
    public static function isSmsNotificationsEnabled()
    {
        return self::get('sms_notifications', false);
    }

    /**
     * Get session timeout in minutes
     */
    public static function getSessionTimeout()
    {
        return self::get('session_timeout', 30);
    }

    /**
     * Get maximum file upload size in MB
     */
    public static function getMaxFileUploadSize()
    {
        return self::get('file_upload_max_size', 5);
    }

    /**
     * Get allowed file types
     */
    public static function getAllowedFileTypes()
    {
        $types = self::get('allowed_file_types', 'jpg,jpeg,png,pdf,doc,docx');
        return explode(',', $types);
    }

    /**
     * Get all public settings
     */
    public static function getPublicSettings()
    {
        return Setting::getPublicSettings();
    }

    /**
     * Get settings by group
     */
    public static function getSettingsByGroup($group)
    {
        return Setting::getByGroup($group);
    }

    /**
     * Get all settings grouped
     */
    public static function getAllSettingsGrouped()
    {
        return Setting::getGroupedSettings();
    }

    // ==================== TIMING SETTINGS ====================

    /**
     * Get class start time
     */
    public static function getClassStartTime()
    {
        return self::get('class_start_time', '08:00');
    }

    /**
     * Get class interval time in minutes
     */
    public static function getClassIntervalMinutes()
    {
        return (int) self::get('class_interval_minutes', 15);
    }

    /**
     * Get per class duration in minutes
     */
    public static function getClassDurationMinutes()
    {
        return (int) self::get('class_duration_minutes', 45);
    }

    /**
     * Get weekly off days
     */
    public static function getWeeklyOffDays()
    {
        $offDays = self::get('weekly_offdays', '["Friday"]');
        if (is_string($offDays)) {
            $decoded = json_decode($offDays, true);
            return is_array($decoded) ? $decoded : ['Friday'];
        }
        return is_array($offDays) ? $offDays : ['Friday'];
    }

    /**
     * Check if a day is an off day
     */
    public static function isOffDay($day)
    {
        $offDays = self::getWeeklyOffDays();
        return in_array($day, $offDays);
    }

    /**
     * Get working days (excluding off days)
     */
    public static function getWorkingDays()
    {
        $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $offDays = self::getWeeklyOffDays();
        return array_diff($allDays, $offDays);
    }

    /**
     * Generate time slots based on settings
     */
    public static function generateTimeSlots()
    {
        $startTime = \Carbon\Carbon::parse(self::getClassStartTime());
        $interval = self::getClassIntervalMinutes();
        $duration = self::getClassDurationMinutes();
        $workingDays = self::getWorkingDays();
        
        $slots = [];
        $currentTime = $startTime->copy();
        $endTime = $currentTime->copy()->addHours(8); // 8 hours of classes
        
        while ($currentTime->lt($endTime)) {
            $endSlot = $currentTime->copy()->addMinutes($duration);
            $slots[] = [
                'start' => $currentTime->format('H:i'),
                'end' => $endSlot->format('H:i'),
                'display' => $currentTime->format(self::getTimeFormat() === '12' ? 'g:i A' : 'H:i') . ' - ' . $endSlot->format(self::getTimeFormat() === '12' ? 'g:i A' : 'H:i'),
                'start_time' => $currentTime->copy(),
                'end_time' => $endSlot->copy(),
            ];
            $currentTime->addMinutes($interval);
        }
        
        return $slots;
    }

    // ==================== FORMAT SETTINGS ====================

    /**
     * Get date format
     */
    public static function getDateFormat()
    {
        return self::get('date_format', 'd/m/Y');
    }

    /**
     * Get time format (12 or 24)
     */
    public static function getTimeFormat()
    {
        return self::get('time_format', '12');
    }

    /**
     * Get timezone
     */
    public static function getTimezone()
    {
        return self::get('timezone', 'Asia/Dhaka');
    }

    /**
     * Get timezone offset
     */
    public static function getTimezoneOffset()
    {
        return self::get('timezone_offset', '+06:00');
    }

    /**
     * Format date according to settings
     */
    public static function formatDate($date, $format = null)
    {
        if (!$date) return '';
        
        $carbon = \Carbon\Carbon::parse($date);
        $carbon->setTimezone(self::getTimezone());
        
        $format = $format ?: self::getDateFormat();
        return $carbon->format($format);
    }

    /**
     * Format time according to settings
     */
    public static function formatTime($time, $format = null)
    {
        if (!$time) return '';
        
        $carbon = \Carbon\Carbon::parse($time);
        $carbon->setTimezone(self::getTimezone());
        
        if ($format) {
            return $carbon->format($format);
        }
        
        return self::getTimeFormat() === '12' 
            ? $carbon->format('g:i A') 
            : $carbon->format('H:i');
    }

    /**
     * Format datetime according to settings
     */
    public static function formatDateTime($datetime, $dateFormat = null, $timeFormat = null)
    {
        if (!$datetime) return '';
        
        $carbon = \Carbon\Carbon::parse($datetime);
        $carbon->setTimezone(self::getTimezone());
        
        $dateFormat = $dateFormat ?: self::getDateFormat();
        $timeFormat = $timeFormat ?: (self::getTimeFormat() === '12' ? 'g:i A' : 'H:i');
        
        return $carbon->format($dateFormat . ' ' . $timeFormat);
    }

    /**
     * Get current time in application timezone
     */
    public static function getCurrentTime()
    {
        return \Carbon\Carbon::now(self::getTimezone());
    }

    /**
     * Get current date in application timezone
     */
    public static function getCurrentDate()
    {
        return \Carbon\Carbon::today(self::getTimezone());
    }

    /**
     * Convert time to application timezone
     */
    public static function convertToAppTimezone($time, $fromTimezone = 'UTC')
    {
        return \Carbon\Carbon::parse($time, $fromTimezone)->setTimezone(self::getTimezone());
    }

    /**
     * Convert time from application timezone to another timezone
     */
    public static function convertFromAppTimezone($time, $toTimezone = 'UTC')
    {
        return \Carbon\Carbon::parse($time, self::getTimezone())->setTimezone($toTimezone);
    }

    /**
     * Get timezone options for dropdown
     */
    public static function getTimezoneOptions()
    {
        return [
            'UTC' => 'UTC (Coordinated Universal Time)',
            'Asia/Dhaka' => 'Asia/Dhaka (UTC+06:00)',
            'Asia/Kolkata' => 'Asia/Kolkata (UTC+05:30)',
            'Asia/Karachi' => 'Asia/Karachi (UTC+05:00)',
            'Asia/Dubai' => 'Asia/Dubai (UTC+04:00)',
            'Asia/Tehran' => 'Asia/Tehran (UTC+03:30)',
            'Europe/London' => 'Europe/London (UTC+00:00)',
            'Europe/Paris' => 'Europe/Paris (UTC+01:00)',
            'America/New_York' => 'America/New_York (UTC-05:00)',
            'America/Los_Angeles' => 'America/Los_Angeles (UTC-08:00)',
        ];
    }

    /**
     * Get date format options for dropdown
     */
    public static function getDateFormatOptions()
    {
        return [
            'd/m/Y' => 'DD/MM/YYYY (e.g., 25/12/2024)',
            'm/d/Y' => 'MM/DD/YYYY (e.g., 12/25/2024)',
            'Y-m-d' => 'YYYY-MM-DD (e.g., 2024-12-25)',
            'd-m-Y' => 'DD-MM-YYYY (e.g., 25-12-2024)',
            'd M Y' => 'DD MMM YYYY (e.g., 25 Dec 2024)',
            'M d, Y' => 'MMM DD, YYYY (e.g., Dec 25, 2024)',
            'l, F j, Y' => 'Day, Month DD, YYYY (e.g., Wednesday, December 25, 2024)',
        ];
    }

    /**
     * Get time format options for dropdown
     */
    public static function getTimeFormatOptions()
    {
        return [
            '12' => '12-hour format (AM/PM)',
            '24' => '24-hour format',
        ];
    }

    /**
     * Get weekly off days options for multi-select
     */
    public static function getWeeklyOffDaysOptions()
    {
        return [
            'Monday' => 'Monday',
            'Tuesday' => 'Tuesday',
            'Wednesday' => 'Wednesday',
            'Thursday' => 'Thursday',
            'Friday' => 'Friday',
            'Saturday' => 'Saturday',
            'Sunday' => 'Sunday',
        ];
    }
}
