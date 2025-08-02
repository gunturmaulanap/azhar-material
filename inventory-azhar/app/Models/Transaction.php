<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function goods()
    {
        return $this->belongsToMany(Goods::class)->withPivot('price', 'qty', 'subtotal', 'delivery');
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function retur()
    {
        return $this->hasOne(ReturTransaction::class);
    }

    public function actDebts()
    {
        return $this->hasMany(ActDebt::class);
    }
}
