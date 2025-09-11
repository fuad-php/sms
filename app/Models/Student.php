<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'class_id',
        'admission_date',
        'roll_number',
        'blood_group',
        'medical_info',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'guardian_address',
        'emergency_contact',
        'mother_name',
        'father_name',
        'birth_registration',
        'is_active',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function parents()
    {
        return $this->belongsToMany(ParentModel::class, 'parent_student', 'student_id', 'parent_id')
                    ->withPivot('relationship')
                    ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
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
    public function getNextRollNumber($classId)
    {
        $lastStudent = static::where('class_id', $classId)
                           ->orderBy('roll_number', 'desc')
                           ->first();
        
        return $lastStudent ? $lastStudent->roll_number + 1 : 1;
    }

    public function getCurrentEnrollment()
    {
        return $this->enrollments()
                   ->where('is_active', true)
                   ->where('status', 'enrolled')
                   ->first();
    }

    public function getEnrollmentHistory()
    {
        return $this->enrollments()
                   ->with(['class', 'section'])
                   ->orderBy('enrolled_at', 'desc')
                   ->get();
    }
    public function getFullNameAttribute()
    {
        return $this->user->name;
    }

    public function getAttendancePercentage($startDate = null, $endDate = null)
    {
        $query = $this->attendances();
        
        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $totalDays = $query->count();
        $presentDays = $query->where('status', 'present')->count();

        return $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;
    }

    public function getCurrentGradeAverage()
    {
        $results = $this->examResults()->with('exam')->get();
        if ($results->isEmpty()) {
            return null;
        }

        $totalMarks = $results->sum('marks_obtained');
        $totalPossible = $results->sum(function ($result) {
            return $result->exam->total_marks;
        });

        return $totalPossible > 0 ? round(($totalMarks / $totalPossible) * 100, 2) : 0;
    }

    /**
     * Generate a unique student ID
     */
    public static function generateStudentId(): string
    {
        $prefix = 'STU-' . date('Y') . '-';
        do {
            $random = str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $candidate = $prefix . $random;
        } while (self::where('student_id', $candidate)->exists());

        return $candidate;
    }

    /**
     * Get the next auto-incremental roll number for a given class
     */
    public static function nextRollNumber(int $classId): int
    {
        // Lock rows for this class to avoid race conditions when assigning roll
        $max = self::where('class_id', $classId)
            ->lockForUpdate()
            ->max('roll_number');

        return ((int) $max) + 1;
    }
}