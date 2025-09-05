<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ManagingCommittee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'designation',
        'position',
        'bio',
        'email',
        'phone',
        'image',
        'sort_order',
        'is_active',
        'is_featured',
        'term_start',
        'term_end',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'term_start' => 'date',
        'term_end' => 'date',
        'sort_order' => 'integer',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        return asset('images/default-avatar.png');
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getPositionDisplayAttribute()
    {
        if ($this->position) {
            $key = match ($this->position) {
                'Chairman' => 'chairman',
                'Vice-Chairman' => 'vice_chairman',
                'Secretary' => 'secretary',
                'Treasurer' => 'treasurer',
                'Advisor' => 'advisor',
                default => null,
            };

            if ($key) {
                return __("app.$key");
            }
            return $this->position;
        }
        return $this->designation;
    }

    public function getTermDurationAttribute()
    {
        if ($this->term_start && $this->term_end) {
            return $this->term_start->translatedFormat('M Y') . ' - ' . $this->term_end->translatedFormat('M Y');
        } elseif ($this->term_start) {
            return __('app.member_since') . ' ' . $this->term_start->translatedFormat('M Y');
        }
        return null;
    }

    // Mutators
    public function setImageAttribute($value)
    {
        if ($value && is_string($value)) {
            $this->attributes['image'] = $value;
        }
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
