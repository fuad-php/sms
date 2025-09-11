<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TeacherPerformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'evaluator_id',
        'evaluation_type',
        'academic_year',
        'semester',
        'evaluation_date',
        'period_start',
        'period_end',
        'teaching_quality',
        'classroom_management',
        'student_engagement',
        'subject_knowledge',
        'communication_skills',
        'professionalism',
        'punctuality',
        'collaboration',
        'innovation',
        'student_feedback',
        'overall_rating',
        'performance_level',
        'classes_taught',
        'students_taught',
        'average_student_grade',
        'attendance_rate',
        'punctuality_rate',
        'strengths',
        'areas_for_improvement',
        'recommendations',
        'evaluator_comments',
        'teacher_response',
        'status',
        'is_approved',
        'approved_by',
        'approved_at',
        'goals',
        'achievements',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
        'approved_at' => 'datetime',
        'goals' => 'array',
        'achievements' => 'array',
        'is_approved' => 'boolean',
    ];

    /**
     * Get the teacher that owns the performance evaluation
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'user_id');
    }

    /**
     * Get the evaluator who conducted the evaluation
     */
    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    /**
     * Get the user who approved the evaluation
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope for active evaluations
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'draft');
    }

    /**
     * Scope for approved evaluations
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for evaluations by academic year
     */
    public function scopeForAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    /**
     * Scope for evaluations by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('evaluation_type', $type);
    }

    /**
     * Scope for evaluations by performance level
     */
    public function scopeByPerformanceLevel($query, $level)
    {
        return $query->where('performance_level', $level);
    }

    /**
     * Scope for recent evaluations
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('evaluation_date', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Get the performance level badge class
     */
    public function getPerformanceLevelBadgeClassAttribute(): string
    {
        return match($this->performance_level) {
            'excellent' => 'bg-green-100 text-green-800',
            'good' => 'bg-blue-100 text-blue-800',
            'satisfactory' => 'bg-yellow-100 text-yellow-800',
            'needs_improvement' => 'bg-orange-100 text-orange-800',
            'poor' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'under_review' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the evaluation type display name
     */
    public function getEvaluationTypeDisplayAttribute(): string
    {
        return match($this->evaluation_type) {
            'annual' => 'Annual Evaluation',
            'quarterly' => 'Quarterly Review',
            'monthly' => 'Monthly Check-in',
            'special' => 'Special Evaluation',
            default => ucfirst($this->evaluation_type),
        };
    }

    /**
     * Get the performance level display name
     */
    public function getPerformanceLevelDisplayAttribute(): string
    {
        return match($this->performance_level) {
            'excellent' => 'Excellent',
            'good' => 'Good',
            'satisfactory' => 'Satisfactory',
            'needs_improvement' => 'Needs Improvement',
            'poor' => 'Poor',
            default => ucfirst($this->performance_level),
        };
    }

    /**
     * Get the status display name
     */
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'under_review' => 'Under Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => ucfirst($this->status),
        };
    }

    /**
     * Calculate the overall rating from individual scores
     */
    public function calculateOverallRating(): float
    {
        $scores = [
            $this->teaching_quality,
            $this->classroom_management,
            $this->student_engagement,
            $this->subject_knowledge,
            $this->communication_skills,
            $this->professionalism,
            $this->punctuality,
            $this->collaboration,
            $this->innovation,
            $this->student_feedback,
        ];

        return round(array_sum($scores) / count($scores), 2);
    }

    /**
     * Determine performance level based on overall rating
     */
    public function determinePerformanceLevel(): string
    {
        $rating = $this->overall_rating;

        if ($rating >= 4.5) return 'excellent';
        if ($rating >= 4.0) return 'good';
        if ($rating >= 3.0) return 'satisfactory';
        if ($rating >= 2.0) return 'needs_improvement';
        return 'poor';
    }

    /**
     * Check if evaluation is overdue
     */
    public function isOverdue(): bool
    {
        return $this->evaluation_date < Carbon::now() && $this->status === 'draft';
    }

    /**
     * Check if evaluation is pending approval
     */
    public function isPendingApproval(): bool
    {
        return in_array($this->status, ['submitted', 'under_review']);
    }

    /**
     * Get the evaluation period duration in days
     */
    public function getPeriodDurationAttribute(): int
    {
        return $this->period_start->diffInDays($this->period_end);
    }

    /**
     * Get formatted evaluation period
     */
    public function getFormattedPeriodAttribute(): string
    {
        return $this->period_start->format('M d, Y') . ' - ' . $this->period_end->format('M d, Y');
    }

    /**
     * Boot method to auto-calculate overall rating and performance level
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->isDirty(['teaching_quality', 'classroom_management', 'student_engagement', 'subject_knowledge', 'communication_skills', 'professionalism', 'punctuality', 'collaboration', 'innovation', 'student_feedback'])) {
                $model->overall_rating = $model->calculateOverallRating();
                $model->performance_level = $model->determinePerformanceLevel();
            }
        });
    }
}