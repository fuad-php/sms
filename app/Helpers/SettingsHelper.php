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
}
