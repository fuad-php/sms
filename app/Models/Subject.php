<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subject', 'subject_id', 'class_id')
                    ->withPivot('teacher_id', 'periods_per_week', 'is_active')
                    ->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'class_subject', 'subject_id', 'teacher_id')
                    ->withPivot('class_id', 'periods_per_week', 'is_active')
                    ->withTimestamps();
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
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
    public function getTeacherForClass($classId)
    {
        $assignment = $this->classes()
                          ->where('class_id', $classId)
                          ->wherePivot('is_active', true)
                          ->first();

        return $assignment ? User::find($assignment->pivot->teacher_id) : null;
    }

    public function getClassesCount()
    {
        return $this->classes()
                   ->wherePivot('is_active', true)
                   ->count();
    }

    public function getTotalPeriodsPerWeek()
    {
        return $this->classes()
                   ->wherePivot('is_active', true)
                   ->sum('class_subject.periods_per_week');
    }
}