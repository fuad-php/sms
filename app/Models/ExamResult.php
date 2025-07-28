<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'marks_obtained',
        'grade',
        'remarks',
        'is_absent',
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'is_absent' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Scopes
     */
    public function scopeForExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopePassed($query)
    {
        return $query->whereHas('exam', function ($q) {
            $q->whereRaw('exam_results.marks_obtained >= exams.passing_marks');
        })->where('is_absent', false);
    }

    public function scopeFailed($query)
    {
        return $query->whereHas('exam', function ($q) {
            $q->whereRaw('exam_results.marks_obtained < exams.passing_marks');
        })->where('is_absent', false);
    }

    public function scopeAbsent($query)
    {
        return $query->where('is_absent', true);
    }

    /**
     * Helper methods
     */
    public function getPercentage()
    {
        if ($this->is_absent) {
            return null;
        }
        
        return round(($this->marks_obtained / $this->exam->total_marks) * 100, 2);
    }

    public function isPassed()
    {
        if ($this->is_absent) {
            return false;
        }
        
        return $this->marks_obtained >= $this->exam->passing_marks;
    }

    public function isFailed()
    {
        if ($this->is_absent) {
            return false;
        }
        
        return $this->marks_obtained < $this->exam->passing_marks;
    }

    public function getGradeCalculated()
    {
        if ($this->is_absent) {
            return 'AB'; // Absent
        }

        if ($this->grade) {
            return $this->grade;
        }

        // Auto-calculate grade based on percentage
        $percentage = $this->getPercentage();
        
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 33) return 'D';
        return 'F';
    }

    public function getGradeBadgeClass()
    {
        $grade = $this->getGradeCalculated();
        
        return match($grade) {
            'A+', 'A' => 'bg-green-100 text-green-800',
            'B+', 'B' => 'bg-blue-100 text-blue-800',
            'C+', 'C' => 'bg-yellow-100 text-yellow-800',
            'D' => 'bg-orange-100 text-orange-800',
            'F' => 'bg-red-100 text-red-800',
            'AB' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusBadgeClass()
    {
        if ($this->is_absent) {
            return 'bg-gray-100 text-gray-800';
        }
        
        return $this->isPassed() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    }

    public function getStatusText()
    {
        if ($this->is_absent) {
            return 'Absent';
        }
        
        return $this->isPassed() ? 'Passed' : 'Failed';
    }
}