<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'visitor_id',
        'ip_address',
        'user_agent',
        'page_visited',
        'referrer',
        'visit_date',
        'visit_time',
    ];

    protected $casts = [
        'visit_date' => 'date',
        // 'visit_time' => 'datetime',
    ];
    public function scopeToday($query)
    {
        return $query->where('visit_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('visit_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('visit_date', now()->month)
            ->whereYear('visit_date', now()->year);
    }
}
