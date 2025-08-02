<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsTransaction extends Model
{
    use HasFactory;

    protected $table = 'goods_transaction';

    protected $fillable = [
        'transaction_id',
        'goods_id',
        'price',
        'qty',
        'subtotal',
        'delivery',
    ];

    // Relasi dengan model Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Relasi dengan model Goods
    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }

    public function deliveryGoods()
    {
        return $this->hasMany(DeliveryGoods::class);
    }
}
