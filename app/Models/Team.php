<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'position',
        'bio',
        'email',
        'phone',
        'photo_url',
        'linkedin_url',
        'instagram_url',
        'facebook_url',
        'twitter_url',
        'skills',
        'sort_order',
        'is_active',
        'is_featured'
    ];

    protected $casts = [
        'skills' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Get the social links as an array for frontend compatibility
     */
    public function getSocialLinksAttribute()
    {
        $links = [];

        if ($this->linkedin_url) {
            $links[] = ['platform' => 'linkedin', 'url' => $this->linkedin_url];
        }
        if ($this->instagram_url) {
            $links[] = ['platform' => 'instagram', 'url' => $this->instagram_url];
        }
        if ($this->facebook_url) {
            $links[] = ['platform' => 'facebook', 'url' => $this->facebook_url];
        }
        if ($this->twitter_url) {
            $links[] = ['platform' => 'twitter', 'url' => $this->twitter_url];
        }

        return $links;
    }

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
     * Scope to get only active team members
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only featured team members
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to order by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
