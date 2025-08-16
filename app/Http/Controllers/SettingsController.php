<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Display the settings index page
     */
    public function index()
    {
        $groupedSettings = Setting::getGroupedSettings();
        
        return view('settings.index', compact('groupedSettings'));
    }

    /**
     * Display settings by group
     */
    public function showGroup($group)
    {
        $settings = Setting::getByGroup($group);
        $groupName = ucwords(str_replace('_', ' ', $group));
        
        return view('settings.group', compact('settings', 'groupName', 'group'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string',
        ]);

        $settings = $request->input('settings', []);
        
        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    /**
     * Update settings for a specific group
     */
    public function updateGroup(Request $request, $group)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string',
        ]);

        $settings = $request->input('settings', []);
        
        Log::info("Updating settings for group: {$group}", ['settings' => $settings]);
        
        try {
            foreach ($settings as $key => $value) {
                Log::info("Processing setting: {$key} = {$value}");
                
                $setting = Setting::where('key', $key)->where('group', $group)->first();
                if ($setting) {
                    Log::info("Found setting: {$setting->id} - {$setting->key} (type: {$setting->type})");
                    
                    $oldValue = $setting->value;
                    $setting->setFormattedValue($value);
                    $setting->save();
                    
                    Log::info("Updated setting: {$key} from '{$oldValue}' to '{$setting->value}'");
                    Cache::forget("setting_{$key}");
                } else {
                    Log::warning("Setting not found: {$key} in group {$group}");
                }
            }

            return redirect()->back()->with('success', ucwords(str_replace('_', ' ', $group)) . ' settings updated successfully!');
        } catch (\Exception $e) {
            Log::error("Error updating settings for group {$group}: " . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'An error occurred while updating settings: ' . $e->getMessage());
        }
    }

    /**
     * Create a new setting
     */
    public function create()
    {
        return view('settings.create');
    }

    /**
     * Store a new setting
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:settings,key|max:255',
            'value' => 'nullable|string',
            'type' => 'required|in:string,text,number,boolean,json',
            'group' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        $setting = Setting::create([
            'key' => $request->key,
            'value' => $request->value,
            'type' => $request->type,
            'group' => $request->group,
            'label' => $request->label,
            'description' => $request->description,
            'is_public' => $request->has('is_public'),
        ]);

        return redirect()->route('settings.index')->with('success', 'Setting created successfully!');
    }

    /**
     * Edit a setting
     */
    public function edit(Setting $setting)
    {
        return view('settings.edit', compact('setting'));
    }

    /**
     * Update a specific setting
     */
    public function updateSetting(Request $request, Setting $setting)
    {
        $request->validate([
            'value' => 'nullable|string',
            'type' => 'required|in:string,text,number,boolean,json',
            'group' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        $setting->update([
            'value' => $request->value,
            'type' => $request->type,
            'group' => $request->group,
            'label' => $request->label,
            'description' => $request->description,
            'is_public' => $request->has('is_public'),
        ]);

        Cache::forget("setting_{$setting->key}");

        return redirect()->route('settings.index')->with('success', 'Setting updated successfully!');
    }

    /**
     * Delete a setting
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();
        Cache::forget("setting_{$setting->key}");

        return redirect()->route('settings.index')->with('success', 'Setting deleted successfully!');
    }

    /**
     * Clear all settings cache
     */
    public function clearCache()
    {
        Setting::clearCache();
        
        return redirect()->back()->with('success', 'Settings cache cleared successfully!');
    }

    /**
     * Reset settings to defaults
     */
    public function resetToDefaults()
    {
        // Delete all existing settings
        Setting::truncate();
        
        // Clear cache
        Setting::clearCache();
        
        // Create default settings
        $this->createDefaultSettings();
        
        return redirect()->route('settings.index')->with('success', 'Settings reset to defaults successfully!');
    }

    /**
     * Create default settings
     */
    private function createDefaultSettings()
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
        ];

        foreach ($defaultSettings as $settingData) {
            Setting::create($settingData);
        }
    }
}
