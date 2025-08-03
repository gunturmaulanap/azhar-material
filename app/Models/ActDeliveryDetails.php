<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActDeliveryDetails extends Model
{
    use HasFactory;

    // Nama tabel (jika tidak sesuai dengan konvensi Laravel)
    protected $table = 'act_delivery_details';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'delivery_id',
        'image',
        'created_at',
    ];

    // Kolom yang tidak dimasukkan ke model (opsional)
    protected $casts = [
        'image' => 'array',
    ];
    protected $guarded = [];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class, 'delivery_id');
    }
}
