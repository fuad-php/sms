<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'name',
        'display_name',
        'description',
        'class_teacher_id',
        'capacity',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function classTeacher()
    {
        return $this->belongsTo(User::class, 'class_teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'section_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'section_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'section_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    /**
     * Helper methods
     */
    public function getFullNameAttribute()
    {
        $className = $this->class->name ?? '';
        $sectionName = $this->display_name ?: $this->name;
        return "{$className} - {$sectionName}";
    }

    public function getStudentCount()
    {
        return $this->students()->where('is_active', true)->count();
    }

    public function getAvailableSeats()
    {
        return $this->capacity - $this->getStudentCount();
    }

    public function isFull()
    {
        return $this->getStudentCount() >= $this->capacity;
    }

    public function getTodayTimetable()
    {
        $today = strtolower(now()->format('l'));
        return $this->timetables()
                    ->with(['subject', 'teacher'])
                    ->where('day_of_week', $today)
                    ->where('is_active', true)
                    ->orderBy('start_time')
                    ->get();
    }

    public function getWeeklyTimetable()
    {
        return $this->timetables()
                    ->with(['subject', 'teacher'])
                    ->where('is_active', true)
                    ->get()
                    ->groupBy('day_of_week');
    }
}
