<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TeacherSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'class_id',
        'subject_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room_number',
        'schedule_type',
        'is_active',
        'effective_from',
        'effective_until',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
        'effective_from' => 'date',
        'effective_until' => 'date',
    ];

    /**
     * Relationships
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'user_id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }

    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('schedule_type', $type);
    }

    public function scopeCurrent($query)
    {
        $today = now()->toDateString();
        return $query->where(function($q) use ($today) {
            $q->whereNull('effective_from')
              ->orWhere('effective_from', '<=', $today);
        })->where(function($q) use ($today) {
            $q->whereNull('effective_until')
              ->orWhere('effective_until', '>=', $today);
        });
    }

    public function scopeConflicting($query, $teacherId, $day, $startTime, $endTime, $excludeId = null)
    {
        $query = $query->where('teacher_id', $teacherId)
                      ->where('day_of_week', $day)
                      ->where('is_active', true);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->where(function($q) use ($startTime, $endTime) {
            $q->whereBetween('start_time', [$startTime, $endTime])
              ->orWhereBetween('end_time', [$startTime, $endTime])
              ->orWhere(function($subQ) use ($startTime, $endTime) {
                  $subQ->where('start_time', '<=', $startTime)
                       ->where('end_time', '>=', $endTime);
              });
        });
    }

    /**
     * Helper Methods
     */
    public function getDurationInMinutes()
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        return $start->diffInMinutes($end);
    }

    public function getDurationFormatted()
    {
        $minutes = $this->getDurationInMinutes();
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0) {
            return $mins > 0 ? "{$hours}h {$mins}m" : "{$hours}h";
        }
        return "{$mins}m";
    }

    public function getTimeRange()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    public function getDayName()
    {
        return ucfirst($this->day_of_week);
    }

    public function getTypeBadgeClass()
    {
        return match($this->schedule_type) {
            'regular' => 'bg-blue-100 text-blue-800',
            'substitute' => 'bg-yellow-100 text-yellow-800',
            'extra' => 'bg-green-100 text-green-800',
            'remedial' => 'bg-purple-100 text-purple-800',
            'exam_prep' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function isConflicting($startTime, $endTime)
    {
        $scheduleStart = Carbon::parse($this->start_time);
        $scheduleEnd = Carbon::parse($this->end_time);
        $newStart = Carbon::parse($startTime);
        $newEnd = Carbon::parse($endTime);

        return $scheduleStart->lt($newEnd) && $scheduleEnd->gt($newStart);
    }

    public function isEffective()
    {
        $today = now()->toDateString();
        
        if ($this->effective_from && $this->effective_from > $today) {
            return false;
        }
        
        if ($this->effective_until && $this->effective_until < $today) {
            return false;
        }
        
        return true;
    }

    public function getStatusBadgeClass()
    {
        if (!$this->is_active) {
            return 'bg-red-100 text-red-800';
        }
        
        if (!$this->isEffective()) {
            return 'bg-yellow-100 text-yellow-800';
        }
        
        return 'bg-green-100 text-green-800';
    }

    public function getStatusText()
    {
        if (!$this->is_active) {
            return 'Inactive';
        }
        
        if (!$this->isEffective()) {
            return 'Expired';
        }
        
        return 'Active';
    }

    /**
     * Get formatted start time according to settings
     */
    public function getFormattedStartTimeAttribute()
    {
        return \App\Helpers\SettingsHelper::formatTime($this->start_time);
    }

    /**
     * Get formatted end time according to settings
     */
    public function getFormattedEndTimeAttribute()
    {
        return \App\Helpers\SettingsHelper::formatTime($this->end_time);
    }

    /**
     * Get formatted time range according to settings
     */
    public function getFormattedTimeRangeAttribute()
    {
        return $this->formatted_start_time . ' - ' . $this->formatted_end_time;
    }
}
