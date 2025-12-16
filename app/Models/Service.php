<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'icon_url',
        'full_description',
        'features',
        'packages',
        'image_url',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'features' => 'array',
        'packages' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });

        static::updating(function ($service) {
            if (empty($service->slug) || $service->isDirty('title')) {
                $service->slug = Str::slug($service->title);
            }
        });
    }

    /**
     * Scope to get only active services
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the URL for the service
     */
    public function getUrlAttribute()
    {
        return route('services.show', $this->slug);
    }
}
