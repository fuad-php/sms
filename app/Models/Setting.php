<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $cacheKey = "setting_{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set setting value by key
     */
    public static function setValue($key, $value)
    {
        $setting = static::where('key', $key)->first();
        
        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            $setting = static::create([
                'key' => $key,
                'value' => $value,
                'type' => 'string',
                'group' => 'general',
                'label' => ucwords(str_replace('_', ' ', $key)),
            ]);
        }

        // Clear cache
        Cache::forget("setting_{$key}");
        
        return $setting;
    }

    /**
     * Get all settings grouped by their group
     */
    public static function getGroupedSettings()
    {
        return static::orderBy('group')
                    ->orderBy('label')
                    ->get()
                    ->groupBy('group');
    }

    /**
     * Get settings by group
     */
    public static function getByGroup($group)
    {
        return static::where('group', $group)
                    ->orderBy('label')
                    ->get();
    }

    /**
     * Get public settings
     */
    public static function getPublicSettings()
    {
        return static::where('is_public', true)
                    ->orderBy('group')
                    ->orderBy('label')
                    ->get();
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        $settings = static::all();
        foreach ($settings as $setting) {
            Cache::forget("setting_{$setting->key}");
        }
    }

    /**
     * Get formatted value based on type
     */
    public function getFormattedValueAttribute()
    {
        switch ($this->type) {
            case 'boolean':
                return (bool) $this->value;
            case 'number':
                return is_numeric($this->value) ? (float) $this->value : 0;
            case 'json':
                return json_decode($this->value, true);
            default:
                return $this->value;
        }
    }

    /**
     * Set value with proper formatting
     */
    public function setFormattedValue($value)
    {
        switch ($this->type) {
            case 'boolean':
                $this->value = $value ? '1' : '0';
                break;
            case 'number':
                $this->value = is_numeric($value) ? (string) $value : '0';
                break;
            case 'json':
                $this->value = is_array($value) ? json_encode($value) : $value;
                break;
            default:
                $this->value = (string) $value;
        }
    }
}
