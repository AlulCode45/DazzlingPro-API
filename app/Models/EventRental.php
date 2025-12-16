<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRental extends Model
{
    protected $table = 'event_rentals';

    protected $fillable = [
        'name',
        'category',
        'specifications',
        'price_per_day',
        'is_featured',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'is_featured' => 'boolean',
    ];
}