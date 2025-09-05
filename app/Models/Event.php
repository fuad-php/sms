<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'start_at',
        'end_at',
        'type',
        'color',
        'is_published',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_at', '>=', Carbon::now());
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}


