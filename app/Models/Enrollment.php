<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'class_id',
        'section_id',
        'academic_year',
        'enrolled_at',
        'status',
        'notes',
        'enrollment_number',
        'is_active',
        'promoted_from',
        'transferred_to',
        'withdrawal_reason',
        'withdrawal_date',
    ];

    protected $casts = [
        'enrolled_at' => 'date',
        'withdrawal_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function promotedFrom()
    {
        return $this->belongsTo(Enrollment::class, 'promoted_from');
    }

    public function transferredTo()
    {
        return $this->belongsTo(Enrollment::class, 'transferred_to');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeBySection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_active', true)
                    ->where('status', 'enrolled');
    }

    /**
     * Helper methods
     */
    public function isEnrolled()
    {
        return $this->status === 'enrolled' && $this->is_active;
    }

    public function isPromoted()
    {
        return $this->status === 'promoted';
    }

    public function isTransferred()
    {
        return $this->status === 'transferred';
    }

    public function isGraduated()
    {
        return $this->status === 'graduated';
    }

    public function isWithdrawn()
    {
        return $this->status === 'withdrawn';
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'enrolled' => 'bg-blue-100 text-blue-800',
            'promoted' => 'bg-green-100 text-green-800',
            'transferred' => 'bg-yellow-100 text-yellow-800',
            'graduated' => 'bg-purple-100 text-purple-800',
            'withdrawn' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getDurationInDays()
    {
        if (!$this->enrolled_at) return 0;
        
        $endDate = $this->is_active ? now() : $this->withdrawal_date ?? now();
        return $this->enrolled_at->diffInDays($endDate);
    }

    /**
     * Auto-generate enrollment number
     */
    public static function generateEnrollmentNumber($academicYear, $classId)
    {
        $year = substr($academicYear, 0, 4);
        $prefix = "ENR{$year}";
        
        $lastEnrollment = static::where('academic_year', $academicYear)
                              ->where('class_id', $classId)
                              ->orderBy('id', 'desc')
                              ->first();
        
        $sequence = $lastEnrollment ? 
                   (int) substr($lastEnrollment->enrollment_number, -4) + 1 : 1;
        
        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Sync student's current class when enrollment is created/updated
     */
    public function syncStudentClass()
    {
        if ($this->isEnrolled()) {
            $this->student->update([
                'class_id' => $this->class_id,
                'roll_number' => $this->student->roll_number ?? $this->student->getNextRollNumber($this->class_id)
            ]);
        }
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($enrollment) {
            if (!$enrollment->enrollment_number) {
                $enrollment->enrollment_number = static::generateEnrollmentNumber(
                    $enrollment->academic_year, 
                    $enrollment->class_id
                );
            }
        });

        static::created(function ($enrollment) {
            $enrollment->syncStudentClass();
        });

        static::updated(function ($enrollment) {
            $enrollment->syncStudentClass();
        });
    }
}
