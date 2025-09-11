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
        'department',
        'phone_extension',
        'office_location',
        'bio',
        'certifications',
        'awards',
        'research_interests',
        'publications',
        'teaching_experience_years',
        'previous_institutions',
        'emergency_contact',
        'emergency_phone',
        'bank_account',
        'tax_id',
        'contract_type',
        'contract_start_date',
        'contract_end_date',
        'performance_rating',
        'last_evaluation_date',
        'notes',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'last_evaluation_date' => 'date',
        'certifications' => 'array',
        'awards' => 'array',
        'research_interests' => 'array',
        'publications' => 'array',
        'previous_institutions' => 'array',
        'performance_rating' => 'decimal:2',
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

    public function subjectsWithClass()
    {
        return $this->belongsToMany(Subject::class, 'class_subject', 'teacher_id', 'subject_id')
                    ->withPivot('class_id', 'periods_per_week', 'is_active')
                    ->withTimestamps()
                    ->with('classes');
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
    public function getSubjectsForClass($classId)
    {
        return $this->subjects()
                    ->wherePivot('class_id', $classId)
                    ->wherePivot('is_active', true)
                    ->get();
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class, 'teacher_id', 'user_id');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'user_id', 'user_id');
    }

    public function performanceEvaluations()
    {
        return $this->hasMany(TeacherPerformance::class, 'teacher_id', 'user_id');
    }

    public function schedules()
    {
        return $this->hasMany(TeacherSchedule::class, 'teacher_id', 'user_id');
    }

    /**
     * Enhanced Scopes
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByDesignation($query, $designation)
    {
        return $query->where('designation', $designation);
    }

    public function scopeByContractType($query, $contractType)
    {
        return $query->where('contract_type', $contractType);
    }

    public function scopeExpiringContracts($query, $days = 30)
    {
        return $query->where('contract_end_date', '<=', now()->addDays($days))
                    ->where('contract_end_date', '>=', now());
    }

    public function scopeHighPerformers($query, $rating = 4.0)
    {
        return $query->where('performance_rating', '>=', $rating);
    }

    /**
     * Enhanced Helper Methods
     */
    public function getFullNameAttribute()
    {
        return $this->user->name;
    }

    public function getEmailAttribute()
    {
        return $this->user->email;
    }

    public function getPhoneAttribute()
    {
        return $this->user->phone;
    }

    public function getExperienceInYears()
    {
        return $this->joining_date ? $this->joining_date->diffInYears(now()) : 0;
    }

    public function getContractStatus()
    {
        if (!$this->contract_end_date) return 'permanent';
        
        if ($this->contract_end_date < now()) return 'expired';
        if ($this->contract_end_date <= now()->addDays(30)) return 'expiring';
        
        return 'active';
    }

    public function getWorkload()
    {
        // If subjects are already loaded as a relationship, count them directly
        if ($this->relationLoaded('subjects')) {
            return $this->subjects->where('pivot.is_active', true)->count();
        }
        
        // Otherwise, use the query builder
        return $this->subjects()->wherePivot('is_active', true)->count();
    }

    public function getTotalStudents()
    {
        // If subjects are already loaded as a relationship, count students directly
        if ($this->relationLoaded('subjects')) {
            $totalStudents = 0;
            foreach ($this->subjects->where('pivot.is_active', true) as $subject) {
                $totalStudents += $subject->getStudentsCount();
            }
            return $totalStudents;
        }
        
        // Otherwise, return 0 for now since students relationship is not properly implemented
        return 0;
    }

    public function getPerformanceRating()
    {
        return $this->performance_rating ?? 0;
    }

    public function getPerformanceLevel()
    {
        $rating = $this->getPerformanceRating();
        
        if ($rating >= 4.5) return 'excellent';
        if ($rating >= 4.0) return 'very_good';
        if ($rating >= 3.5) return 'good';
        if ($rating >= 3.0) return 'satisfactory';
        
        return 'needs_improvement';
    }

    public function getPerformanceBadgeClass()
    {
        return match($this->getPerformanceLevel()) {
            'excellent' => 'bg-green-100 text-green-800',
            'very_good' => 'bg-blue-100 text-blue-800',
            'good' => 'bg-yellow-100 text-yellow-800',
            'satisfactory' => 'bg-orange-100 text-orange-800',
            'needs_improvement' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTodaySchedule()
    {
        $today = strtolower(now()->format('l'));
        return $this->schedules()
                    ->with(['class', 'subject'])
                    ->where('day_of_week', $today)
                    ->where('is_active', true)
                    ->current()
                    ->orderBy('start_time')
                    ->get();
    }

    public function getWeeklySchedule()
    {
        return $this->schedules()
                    ->with(['class', 'subject'])
                    ->where('is_active', true)
                    ->current()
                    ->orderBy('day_of_week')
                    ->orderBy('start_time')
                    ->get()
                    ->groupBy('day_of_week');
    }

    public function getWorkloadByDay($day)
    {
        $schedules = $this->schedules()
                          ->where('day_of_week', $day)
                          ->where('is_active', true)
                          ->current()
                          ->get();
        
        $totalMinutes = 0;
        foreach ($schedules as $schedule) {
            $totalMinutes += $schedule->getDurationInMinutes();
        }
        
        return $totalMinutes;
    }

    public function getTotalWeeklyWorkload()
    {
        $totalMinutes = 0;
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        foreach ($days as $day) {
            $totalMinutes += $this->getWorkloadByDay($day);
        }
        
        return $totalMinutes;
    }

    public function getWorkloadDistribution()
    {
        $distribution = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        foreach ($days as $day) {
            $minutes = $this->getWorkloadByDay($day);
            $distribution[$day] = [
                'minutes' => $minutes,
                'hours' => round($minutes / 60, 2),
                'formatted' => $this->formatMinutes($minutes)
            ];
        }
        
        return $distribution;
    }

    public function getWorkloadStats()
    {
        $weeklyWorkload = $this->getTotalWeeklyWorkload();
        $dailyAverage = $weeklyWorkload / 7;
        
        return [
            'weekly_total_minutes' => $weeklyWorkload,
            'weekly_total_hours' => round($weeklyWorkload / 60, 2),
            'daily_average_minutes' => round($dailyAverage, 2),
            'daily_average_hours' => round($dailyAverage / 60, 2),
            'formatted_weekly' => $this->formatMinutes($weeklyWorkload),
            'formatted_daily_avg' => $this->formatMinutes($dailyAverage)
        ];
    }

    public function hasScheduleConflict($day, $startTime, $endTime, $excludeId = null)
    {
        return $this->schedules()
                    ->conflicting($this->user_id, $day, $startTime, $endTime, $excludeId)
                    ->exists();
    }

    public function getScheduleConflicts($day, $startTime, $endTime, $excludeId = null)
    {
        return $this->schedules()
                    ->conflicting($this->user_id, $day, $startTime, $endTime, $excludeId)
                    ->with(['class', 'subject'])
                    ->get();
    }

    public function getAvailableTimeSlots($day, $duration = 60)
    {
        $existingSchedules = $this->schedules()
                                 ->where('day_of_week', $day)
                                 ->where('is_active', true)
                                 ->current()
                                 ->orderBy('start_time')
                                 ->get();

        $availableSlots = [];
        $startHour = 8; // 8 AM
        $endHour = 17; // 5 PM
        
        for ($hour = $startHour; $hour < $endHour; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 30) { // 30-minute intervals
                $slotStart = sprintf('%02d:%02d', $hour, $minute);
                $slotEnd = sprintf('%02d:%02d', $hour + floor(($minute + $duration) / 60), ($minute + $duration) % 60);
                
                if ($hour + floor(($minute + $duration) / 60) > $endHour) {
                    break;
                }
                
                $hasConflict = false;
                foreach ($existingSchedules as $schedule) {
                    if ($schedule->isConflicting($slotStart, $slotEnd)) {
                        $hasConflict = true;
                        break;
                    }
                }
                
                if (!$hasConflict) {
                    $availableSlots[] = [
                        'start_time' => $slotStart,
                        'end_time' => $slotEnd,
                        'duration' => $duration
                    ];
                }
            }
        }
        
        return $availableSlots;
    }

    private function formatMinutes($minutes)
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0) {
            return $mins > 0 ? "{$hours}h {$mins}m" : "{$hours}h";
        }
        return "{$mins}m";
    }

    public function getTimetables()
    {
        $today = strtolower(now()->format('l'));
        return $this->timetables()
                    ->with(['class', 'subject'])
                    ->where('day_of_week', $today)
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