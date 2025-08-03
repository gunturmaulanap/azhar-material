<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'button_text',
        'button_url',
        'background_image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getBackgroundImageUrlAttribute()
    {
        if ($this->background_image) {
            return asset('storage/' . $this->background_image);
        }
        return null;
    }
} 