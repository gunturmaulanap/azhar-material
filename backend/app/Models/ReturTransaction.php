<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
}
