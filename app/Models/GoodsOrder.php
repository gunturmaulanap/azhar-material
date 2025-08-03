<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsOrder extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'goods_order';
}
