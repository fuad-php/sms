<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarouselSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'button_text',
        'button_url',
        'image',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected $appends = [
        'image_url',
        'title_localized',
        'subtitle_localized',
        'description_localized',
        'button_text_localized',
    ];

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-carousel.jpg');
    }

    public function translations()
    {
        return $this->hasMany(CarouselSlideTranslation::class);
    }

    protected function resolveTranslationField(string $field)
    {
        $locale = app()->getLocale();
        $translation = $this->translations->firstWhere('locale', $locale)
            ?: $this->translations->firstWhere('locale', 'en');
        return $translation?->$field ?: $this->$field;
    }

    public function getTitleLocalizedAttribute()
    {
        return $this->resolveTranslationField('title');
    }

    public function getSubtitleLocalizedAttribute()
    {
        return $this->resolveTranslationField('subtitle');
    }

    public function getDescriptionLocalizedAttribute()
    {
        return $this->resolveTranslationField('description');
    }

    public function getButtonTextLocalizedAttribute()
    {
        return $this->resolveTranslationField('button_text');
    }

    /**
     * Scope to get only active slides
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by slide order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
