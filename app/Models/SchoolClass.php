<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'section',
        'description',
        'class_teacher_id',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function classTeacher()
    {
        return $this->belongsTo(User::class, 'class_teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject', 'class_id', 'subject_id')
                    ->withPivot('teacher_id', 'periods_per_week', 'is_active')
                    ->withTimestamps();
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'class_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'class_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'class_id');
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
        return $this->section ? "{$this->name} - {$this->section}" : $this->name;
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

    public function getClassAttendanceToday()
    {
        $today = now()->format('Y-m-d');
        $totalStudents = $this->getStudentCount();
        $presentStudents = $this->students()
                               ->whereHas('attendances', function ($query) use ($today) {
                                   $query->where('date', $today)
                                         ->where('status', 'present');
                               })
                               ->count();

        return [
            'total' => $totalStudents,
            'present' => $presentStudents,
            'absent' => $totalStudents - $presentStudents,
            'percentage' => $totalStudents > 0 ? round(($presentStudents / $totalStudents) * 100, 2) : 0,
        ];
    }
}