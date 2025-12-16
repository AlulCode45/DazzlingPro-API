<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'primary_button_text',
        'primary_button_url',
        'secondary_button_text',
        'secondary_button_url',
        'background_image_url',
        'background_video_url',
        'overlay_opacity',
        'text_color',
        'button_style',
        'animation_type',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'overlay_opacity' => 'decimal:2',
        'sort_order' => 'integer'
    ];

    /**
     * Get the order field for frontend compatibility
     */
    public function getOrderAttribute()
    {
        return $this->sort_order;
    }

    /**
     * Set the order field for frontend compatibility
     */
    public function setOrderAttribute($value)
    {
        $this->attributes['sort_order'] = $value;
    }

    /**
     * Scope to get only active hero sections
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
}
