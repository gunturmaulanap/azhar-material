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
        'background_video',
        'background_type',
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

    public function getBackgroundVideoUrlAttribute()
    {
        if ($this->background_video) {
            return asset('storage/' . $this->background_video);
        }
        return null;
    }

    public function getBackgroundUrlAttribute()
    {
        if ($this->background_type === 'video' && $this->background_video) {
            return $this->background_video_url;
        } elseif ($this->background_type === 'image' && $this->background_image) {
            return $this->background_image_url;
        }
        return null;
    }
} 