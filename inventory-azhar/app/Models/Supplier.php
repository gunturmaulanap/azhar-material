<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'name',
        'phone',
        'address',
        'description',
    ];

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->where(function ($query) use ($term) {
            $query->where('company', 'like', $term)
                ->orWhere('name', 'like', $term)
                ->orWhere('phone', 'like', $term);
        });
    }
}
