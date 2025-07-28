<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'user_id',
        'occupation',
        'workplace',
        'income_range',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'parent_student', 'parent_id', 'student_id')
                    ->withPivot('relationship')
                    ->withTimestamps();
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

    public function getChildrenCount()
    {
        return $this->students()->count();
    }

    public function getChildrenInClass($classId)
    {
        return $this->students()
                    ->where('class_id', $classId)
                    ->get();
    }

    public function getAllChildrenAttendance($startDate = null, $endDate = null)
    {
        $attendance = [];
        foreach ($this->students as $student) {
            $attendance[$student->id] = [
                'student' => $student,
                'percentage' => $student->getAttendancePercentage($startDate, $endDate),
            ];
        }
        return $attendance;
    }

    public function getAllChildrenGrades()
    {
        $grades = [];
        foreach ($this->students as $student) {
            $grades[$student->id] = [
                'student' => $student,
                'average' => $student->getCurrentGradeAverage(),
            ];
        }
        return $grades;
    }
}