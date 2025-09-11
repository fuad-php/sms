<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class BookIssue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'book_id', 'student_id', 'teacher_id', 'staff_id', 'issued_by',
        'returned_by', 'issue_date', 'due_date', 'return_date',
        'actual_return_date', 'status', 'renewal_count', 'max_renewals',
        'last_renewal_date', 'fine_amount', 'penalty_amount', 'total_fine',
        'fine_paid', 'fine_paid_date', 'fine_notes', 'issue_condition',
        'return_condition', 'condition_notes', 'damage_description',
        'damage_penalty', 'reminder_sent', 'last_reminder_date',
        'reminder_count', 'overdue_notification_sent', 'academic_year',
        'semester', 'purpose', 'notes', 'barcode', 'qr_code',
        'is_digital_copy', 'digital_access_url', 'digital_access_expires_at',
        'requires_approval', 'is_approved', 'approved_by', 'approved_at',
        'approval_notes', 'is_reservation', 'reservation_date',
        'reservation_expires_at', 'reservation_fulfilled', 'days_issued',
        'days_overdue', 'is_extended'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'actual_return_date' => 'date',
        'last_renewal_date' => 'date',
        'fine_paid_date' => 'date',
        'last_reminder_date' => 'date',
        'reservation_date' => 'date',
        'reservation_expires_at' => 'date',
        'digital_access_expires_at' => 'datetime',
        'approved_at' => 'datetime',
        'fine_amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'total_fine' => 'decimal:2',
        'damage_penalty' => 'decimal:2',
        'fine_paid' => 'boolean',
        'reminder_sent' => 'boolean',
        'overdue_notification_sent' => 'boolean',
        'is_digital_copy' => 'boolean',
        'requires_approval' => 'boolean',
        'is_approved' => 'boolean',
        'is_reservation' => 'boolean',
        'reservation_fulfilled' => 'boolean',
        'is_extended' => 'boolean',
        'renewal_count' => 'integer',
        'max_renewals' => 'integer',
        'reminder_count' => 'integer',
        'days_issued' => 'integer',
        'days_overdue' => 'integer',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'user_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function returnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getBorrowerAttribute()
    {
        if ($this->student_id) {
            return $this->student;
        } elseif ($this->teacher_id) {
            return $this->teacher;
        } elseif ($this->staff_id) {
            return $this->staff;
        }
        return null;
    }

    public function getBorrowerNameAttribute(): string
    {
        $borrower = $this->borrower;
        if (!$borrower) return 'Unknown';
        
        if ($this->student_id) {
            return $borrower->user->name ?? 'Unknown Student';
        } elseif ($this->teacher_id) {
            return $borrower->user->name ?? 'Unknown Teacher';
        } elseif ($this->staff_id) {
            return $borrower->user->name ?? 'Unknown Staff';
        }
        
        return 'Unknown';
    }

    public function getBorrowerTypeAttribute(): string
    {
        if ($this->student_id) return 'Student';
        if ($this->teacher_id) return 'Teacher';
        if ($this->staff_id) return 'Staff';
        return 'Unknown';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'issued');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->where('due_date', '<', Carbon::today());
    }

    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }

    public function scopeDamaged($query)
    {
        return $query->where('status', 'damaged');
    }

    public function scopeReservations($query)
    {
        return $query->where('is_reservation', true);
    }

    public function scopeActiveReservations($query)
    {
        return $query->where('is_reservation', true)
                    ->where('reservation_fulfilled', false)
                    ->where('reservation_expires_at', '>', Carbon::now());
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeByStaff($query, $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    public function scopeByBook($query, $bookId)
    {
        return $query->where('book_id', $bookId);
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeRequiringApproval($query)
    {
        return $query->where('requires_approval', true)
                    ->where('is_approved', false);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('issue_date', '>=', Carbon::now()->subDays($days));
    }

    // Accessors
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'issued' => 'bg-blue-100 text-blue-800',
            'returned' => 'bg-green-100 text-green-800',
            'overdue' => 'bg-red-100 text-red-800',
            'lost' => 'bg-gray-100 text-gray-800',
            'damaged' => 'bg-orange-100 text-orange-800',
            'renewed' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getConditionBadgeClassAttribute(): string
    {
        return match($this->issue_condition) {
            'excellent' => 'bg-green-100 text-green-800',
            'good' => 'bg-blue-100 text-blue-800',
            'fair' => 'bg-yellow-100 text-yellow-800',
            'poor' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getReturnConditionBadgeClassAttribute(): string
    {
        if (!$this->return_condition) return 'bg-gray-100 text-gray-800';
        
        return match($this->return_condition) {
            'excellent' => 'bg-green-100 text-green-800',
            'good' => 'bg-blue-100 text-blue-800',
            'fair' => 'bg-yellow-100 text-yellow-800',
            'poor' => 'bg-orange-100 text-orange-800',
            'damaged' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'issued' => 'Issued',
            'returned' => 'Returned',
            'overdue' => 'Overdue',
            'lost' => 'Lost',
            'damaged' => 'Damaged',
            'renewed' => 'Renewed',
            default => ucfirst($this->status),
        };
    }

    public function getIssueDurationAttribute(): int
    {
        $endDate = $this->return_date ?: Carbon::today();
        return $this->issue_date->diffInDays($endDate);
    }

    public function getDaysUntilDueAttribute(): int
    {
        if ($this->status === 'returned') return 0;
        return max(0, Carbon::today()->diffInDays($this->due_date, false));
    }

    public function getDaysOverdueAttribute(): int
    {
        if ($this->status === 'returned') return 0;
        return max(0, $this->due_date->diffInDays(Carbon::today()));
    }

    public function isOverdue(): bool
    {
        return $this->status === 'issued' && $this->due_date < Carbon::today();
    }

    public function canBeRenewed(): bool
    {
        return $this->status === 'issued' && 
               $this->renewal_count < $this->max_renewals &&
               !$this->isOverdue();
    }

    public function isReservation(): bool
    {
        return $this->is_reservation;
    }

    public function isReservationExpired(): bool
    {
        return $this->is_reservation && 
               $this->reservation_expires_at < Carbon::now();
    }

    public function requiresApproval(): bool
    {
        return $this->requires_approval && !$this->is_approved;
    }

    public function calculateFine(): float
    {
        if ($this->status === 'returned' || !$this->isOverdue()) {
            return 0;
        }

        $overdueDays = $this->days_overdue;
        $dailyFine = 1.0; // $1 per day
        
        return min($overdueDays * $dailyFine, 50.0); // Max $50 fine
    }

    public function getTotalFineAmountAttribute(): float
    {
        return $this->fine_amount + $this->penalty_amount + $this->damage_penalty;
    }

    public function isFinePaid(): bool
    {
        return $this->fine_paid;
    }

    public function getIssuePeriodDisplayAttribute(): string
    {
        return $this->issue_date->format('M d, Y') . ' - ' . 
               ($this->return_date ? $this->return_date->format('M d, Y') : $this->due_date->format('M d, Y'));
    }

    public function getDueDateDisplayAttribute(): string
    {
        return $this->due_date->format('M d, Y');
    }

    public function getReturnDateDisplayAttribute(): string
    {
        return $this->return_date ? $this->return_date->format('M d, Y') : 'Not returned';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($issue) {
            if (!$issue->issue_date) {
                $issue->issue_date = Carbon::today();
            }
            if (!$issue->due_date) {
                $issue->due_date = Carbon::today()->addDays(14);
            }
            if (!$issue->academic_year) {
                $issue->academic_year = Carbon::now()->year;
            }
        });

        static::saving(function ($issue) {
            if ($issue->issue_date) {
                $endDate = $issue->return_date ?: Carbon::today();
                $issue->days_issued = $issue->issue_date->diffInDays($endDate);
            }

            if ($issue->status === 'issued' && $issue->due_date < Carbon::today()) {
                $issue->days_overdue = $issue->due_date->diffInDays(Carbon::today());
            } else {
                $issue->days_overdue = 0;
            }

            if ($issue->isOverdue()) {
                $issue->fine_amount = $issue->calculateFine();
            }

            if ($issue->status === 'issued' && $issue->isOverdue()) {
                $issue->status = 'overdue';
            }
        });
    }
}