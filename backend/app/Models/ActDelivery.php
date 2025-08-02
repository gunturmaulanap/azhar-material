<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActDelivery extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class); // Pastikan Anda memiliki model Good
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Pastikan Anda memiliki model User
    }
}
