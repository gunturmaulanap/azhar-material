<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'image',
        'external_image_url',
        'category',
        'client',
        'location',
        'weight',
        'year',
        'date',
        'status',
        'featured',
        'sort_order'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'year' => 'integer',
        'sort_order' => 'integer'
    ];

    // This line tells Laravel to automatically append the 'display_image_url' accessor
    // to the JSON output when fetching projects.
    protected $appends = ['display_image_url'];

    // Scopes
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/placeholder-project.jpg');
    }

    public function getDisplayImageUrlAttribute()
    {
        if ($this->external_image_url) {
            return $this->external_image_url;
        }
        return $this->image_url;
    }
}
