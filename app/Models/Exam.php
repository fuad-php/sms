<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'class_id',
        'subject_id',
        'exam_date',
        'start_time',
        'end_time',
        'total_marks',
        'passing_marks',
        'exam_type',
        'is_published',
        'academic_year',
        'exam_session',
        'exam_venue',
        'instructions',
        'duration_minutes',
        'weightage',
        'is_online',
        'max_attempts',
        'late_submission_allowed',
        'late_submission_penalty',
        'created_by',
        'approved_by',
        'approval_date',
        'status',
        'grade_scale',
        'negative_marking',
        'negative_marking_ratio',
        'exam_code',
        'is_archived',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_published' => 'boolean',
        'approval_date' => 'date',
        'is_online' => 'boolean',
        'late_submission_allowed' => 'boolean',
        'negative_marking' => 'boolean',
        'is_archived' => 'boolean',
        'weightage' => 'decimal:2',
        'late_submission_penalty' => 'decimal:2',
        'negative_marking_ratio' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function examSessions()
    {
        return $this->hasMany(ExamSession::class);
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeForSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('exam_date', '>=', now()->toDateString());
    }

    public function scopePast($query)
    {
        return $query->where('exam_date', '<', now()->toDateString());
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeBySession($query, $session)
    {
        return $query->where('exam_session', $session);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOnline($query)
    {
        return $query->where('is_online', true);
    }

    public function scopeOffline($query)
    {
        return $query->where('is_online', false);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Helper methods
     */
    public function getDurationInMinutes()
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
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

    public function isUpcoming()
    {
        return $this->exam_date >= now()->toDateString();
    }

    public function isPast()
    {
        return $this->exam_date < now()->toDateString();
    }

    public function isToday()
    {
        return $this->exam_date->isToday();
    }

    public function getResultsCount()
    {
        return $this->results()->count();
    }

    public function getExpectedResultsCount()
    {
        return $this->class->getStudentCount();
    }

    public function getResultsCompletionPercentage()
    {
        $expected = $this->getExpectedResultsCount();
        $completed = $this->getResultsCount();
        
        return $expected > 0 ? round(($completed / $expected) * 100, 2) : 0;
    }

    public function getAverageMarks()
    {
        return $this->results()
                   ->where('is_absent', false)
                   ->avg('marks_obtained');
    }

    public function getPassPercentage()
    {
        $totalResults = $this->results()->where('is_absent', false)->count();
        $passedResults = $this->results()
                             ->where('is_absent', false)
                             ->where('marks_obtained', '>=', $this->passing_marks)
                             ->count();
        
        return $totalResults > 0 ? round(($passedResults / $totalResults) * 100, 2) : 0;
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getExamTypeBadgeClass()
    {
        return match($this->exam_type) {
            'written' => 'bg-blue-100 text-blue-800',
            'oral' => 'bg-green-100 text-green-800',
            'practical' => 'bg-purple-100 text-purple-800',
            'online' => 'bg-indigo-100 text-indigo-800',
            'assignment' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function canBeEdited()
    {
        return in_array($this->status, ['draft', 'pending', 'rejected']);
    }

    public function canBeDeleted()
    {
        return in_array($this->status, ['draft', 'rejected']) && $this->results()->count() === 0;
    }

    public function getGradeDistribution()
    {
        $results = $this->results()->where('is_absent', false)->get();
        
        $distribution = [
            'A+' => 0, 'A' => 0, 'A-' => 0,
            'B+' => 0, 'B' => 0, 'B-' => 0,
            'C+' => 0, 'C' => 0, 'C-' => 0,
            'D' => 0, 'F' => 0
        ];

        foreach ($results as $result) {
            if (isset($distribution[$result->grade])) {
                $distribution[$result->grade]++;
            }
        }

        return $distribution;
    }

    public function getStatistics()
    {
        $results = $this->results()->where('is_absent', false)->get();
        
        if ($results->isEmpty()) {
            return [
                'total_students' => 0,
                'present_students' => 0,
                'absent_students' => 0,
                'average_marks' => 0,
                'highest_marks' => 0,
                'lowest_marks' => 0,
                'pass_percentage' => 0,
                'grade_distribution' => []
            ];
        }

        return [
            'total_students' => $this->class->students()->count(),
            'present_students' => $results->count(),
            'absent_students' => $this->class->students()->count() - $results->count(),
            'average_marks' => round($results->avg('marks_obtained'), 2),
            'highest_marks' => $results->max('marks_obtained'),
            'lowest_marks' => $results->min('marks_obtained'),
            'pass_percentage' => $this->getPassPercentage(),
            'grade_distribution' => $this->getGradeDistribution()
        ];
    }

    public function generateExamCode()
    {
        if (!$this->exam_code) {
            $year = substr($this->academic_year ?? now()->year, 0, 4);
            $classCode = strtoupper(substr($this->class->name, 0, 3));
            $subjectCode = strtoupper(substr($this->subject->name, 0, 3));
            $typeCode = strtoupper(substr($this->exam_type, 0, 2));
            
            $this->exam_code = "{$year}{$classCode}{$subjectCode}{$typeCode}" . str_pad($this->id, 3, '0', STR_PAD_LEFT);
            $this->save();
        }
        
        return $this->exam_code;
    }
}