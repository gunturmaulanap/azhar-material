<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function goods()
    {
        return $this->belongsToMany(Goods::class)->withPivot('id', 'qty', 'delivered');
    }

    public function activities()
    {
        return $this->hasMany(actDelivery::class);
    }
}
