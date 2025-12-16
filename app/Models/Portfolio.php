<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    protected $table = 'portfolios';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'client_name',
        'event_date',
        'event_location',
        'portfolio_category_id',
        'images',
        'featured_image',
        'featured',
        'completed',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'event_date' => 'date',
        'images' => 'array',
        'featured' => 'boolean',
        'completed' => 'boolean',
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PortfolioCategory::class, 'portfolio_category_id');
    }
}