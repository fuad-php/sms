<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarouselSlideTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'carousel_slide_id',
        'locale',
        'title',
        'subtitle',
        'description',
        'button_text',
    ];

    public function slide()
    {
        return $this->belongsTo(CarouselSlide::class, 'carousel_slide_id');
    }
}


