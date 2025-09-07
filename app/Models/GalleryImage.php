<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'is_featured',
        'uploaded_by',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function scopeFeaturedFirst(Builder $query): Builder
    {
        return $query->orderByDesc('is_featured')->orderBy('created_at', 'desc');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getImageUrlAttribute(): string
    {
        $path = $this->image_path;
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }
        return asset('storage/' . ltrim($path, '/'));
    }
}


