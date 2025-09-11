<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class YearlyLeave extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'year',
        'type',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->toDateString());
    }

    public function scopeCurrent($query)
    {
        $today = now()->toDateString();
        return $query->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
    }

    // Accessors
    public function getDurationAttribute()
    {
        return Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)) + 1;
    }

    public function getFormattedDateRangeAttribute()
    {
        $start = Carbon::parse($this->start_date)->format('M d, Y');
        $end = Carbon::parse($this->end_date)->format('M d, Y');
        
        if ($this->start_date == $this->end_date) {
            return $start;
        }
        
        return $start . ' - ' . $end;
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'holiday' => 'Holiday',
            'vacation' => 'Vacation',
            'exam_period' => 'Exam Period',
            'other' => 'Other',
            default => 'Unknown'
        };
    }

    // Methods
    public function isActive()
    {
        return $this->is_active;
    }

    public function isCurrent()
    {
        $today = now()->toDateString();
        return $this->start_date <= $today && $this->end_date >= $today;
    }

    public function isUpcoming()
    {
        return $this->start_date > now()->toDateString();
    }

    public function isPast()
    {
        return $this->end_date < now()->toDateString();
    }
}
