<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'name',
        'description',
        'image',
        'category_id',
        'brand_id',
        'stock',
        'unit',
        'cost',
        'price',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function goodsTransactions()
    {
        return $this->hasMany(GoodsTransaction::class);
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class)->withPivot('price', 'qty', 'subtotal', 'delivery');
    }

    public function scopeSearch($query, $term)
    {
        $term = "%{$term}%";
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', $term)
              ->orWhereHas('brand', function ($b) use ($term) {
                  $b->where('name', 'like', $term);
              })
              ->orWhereHas('category', function ($c) use ($term) {
                  $c->where('name', 'like', $term);
              });
        });
    }
}
