<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'address',
        'phone',
        'email',
        'website',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
        'map_embed',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
