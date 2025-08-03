<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $table = 'about';

    protected $fillable = [
        'title',
        'description',
        'image',
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'company_website',
        'vision',
        'mission',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
