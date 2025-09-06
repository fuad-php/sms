<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'user_id',
        'employee_id',
        'designation',
        'department',
        'salary',
        'joining_date',
        'experience',
        'is_active',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(EmployeeAttendance::class, 'user_id', 'user_id');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'user_id', 'user_id');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'user_id', 'user_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}


