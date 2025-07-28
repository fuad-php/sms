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
    ];

    protected $casts = [
        'exam_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_published' => 'boolean',
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
}