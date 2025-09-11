<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'fee_category_id',
        'name',
        'description',
        'amount',
        'frequency',
        'due_date',
        'installments',
        'is_mandatory',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function feeCategory()
    {
        return $this->belongsTo(FeeCategory::class);
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class);
    }

    public function installments()
    {
        return $this->hasMany(FeeInstallment::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Helper methods
     */
    public function getTotalAmountForStudent($studentId)
    {
        return $this->payments()
            ->where('student_id', $studentId)
            ->where('status', 'completed')
            ->sum('total_amount');
    }

    public function getRemainingAmountForStudent($studentId)
    {
        $totalPaid = $this->getTotalAmountForStudent($studentId);
        return $this->amount - $totalPaid;
    }
}
