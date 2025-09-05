<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'designation',
        'qualification',
        'specialization',
        'salary',
        'joining_date',
        'experience',
        'is_active',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classesAsTeacher()
    {
        return $this->hasMany(SchoolClass::class, 'class_teacher_id', 'user_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject', 'teacher_id', 'subject_id')
                    ->withPivot('class_id', 'periods_per_week', 'is_active')
                    ->withTimestamps();
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'teacher_id', 'user_id');
    }

    public function attendancesMarked()
    {
        return $this->hasMany(Attendance::class, 'marked_by', 'user_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Helper methods
     */
    public function getFullNameAttribute()
    {
        return $this->user->name;
    }

    public function getSubjectsForClass($classId)
    {
        return $this->subjects()
                    ->wherePivot('class_id', $classId)
                    ->wherePivot('is_active', true)
                    ->get();
    }

    public function getTodaySchedule()
    {
        $today = strtolower(now()->format('l'));
        return $this->timetables()
                    ->with(['class', 'subject'])
                    ->where('day_of_week', $today)
                    ->where('is_active', true)
                    ->orderBy('start_time')
                    ->get();
    }

    public function getTotalTeachingHours()
    {
        return $this->timetables()
                    ->where('is_active', true)
                    ->get()
                    ->sum(function ($timetable) {
                        $start = \Carbon\Carbon::parse($timetable->start_time);
                        $end = \Carbon\Carbon::parse($timetable->end_time);
                        return $start->diffInHours($end);
                    });
    }
}