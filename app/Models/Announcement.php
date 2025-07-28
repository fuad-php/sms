<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'created_by',
        'target_audience',
        'class_id',
        'priority',
        'publish_date',
        'expire_date',
        'is_published',
        'attachment',
    ];

    protected $casts = [
        'publish_date' => 'datetime',
        'expire_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeActive($query)
    {
        $now = now();
        return $query->where('is_published', true)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('publish_date')
                          ->orWhere('publish_date', '<=', $now);
                    })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('expire_date')
                          ->orWhere('expire_date', '>=', $now);
                    });
    }

    public function scopeForAudience($query, $audience)
    {
        return $query->where(function ($q) use ($audience) {
            $q->where('target_audience', 'all')
              ->orWhere('target_audience', $audience);
        });
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where(function ($q) use ($classId) {
            $q->whereNull('class_id')
              ->orWhere('class_id', $classId);
        });
    }

    public function scopeByPriority($query, $priority = null)
    {
        if ($priority) {
            return $query->where('priority', $priority);
        }
        
        return $query->orderByRaw("
            CASE priority 
                WHEN 'urgent' THEN 1 
                WHEN 'high' THEN 2 
                WHEN 'medium' THEN 3 
                WHEN 'low' THEN 4 
                ELSE 5 
            END
        ");
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Helper methods
     */
    public function isActive()
    {
        if (!$this->is_published) {
            return false;
        }

        $now = now();
        
        if ($this->publish_date && $this->publish_date > $now) {
            return false;
        }
        
        if ($this->expire_date && $this->expire_date < $now) {
            return false;
        }
        
        return true;
    }

    public function isExpired()
    {
        return $this->expire_date && $this->expire_date < now();
    }

    public function isScheduled()
    {
        return $this->publish_date && $this->publish_date > now();
    }

    public function getPriorityBadgeClass()
    {
        return match($this->priority) {
            'urgent' => 'bg-red-100 text-red-800',
            'high' => 'bg-orange-100 text-orange-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'low' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPriorityIcon()
    {
        return match($this->priority) {
            'urgent' => '🚨',
            'high' => '⚠️',
            'medium' => '📢',
            'low' => 'ℹ️',
            default => '📄',
        };
    }

    public function getTargetAudienceText()
    {
        $audience = match($this->target_audience) {
            'all' => 'Everyone',
            'students' => 'Students',
            'teachers' => 'Teachers',
            'parents' => 'Parents',
            'staff' => 'Staff',
            default => ucfirst($this->target_audience),
        };

        if ($this->class_id && $this->class) {
            $audience .= ' - ' . $this->class->full_name;
        }

        return $audience;
    }

    public function getTimeRemaining()
    {
        if (!$this->expire_date) {
            return null;
        }

        $now = now();
        if ($this->expire_date < $now) {
            return 'Expired';
        }

        return $now->diffForHumans($this->expire_date, true) . ' remaining';
    }

    public function hasAttachment()
    {
        return !empty($this->attachment);
    }

    public function getAttachmentUrl()
    {
        if (!$this->hasAttachment()) {
            return null;
        }

        return asset('storage/announcements/' . $this->attachment);
    }
}