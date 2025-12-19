<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'role',
        'content',
        'rating',
        'image_url',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];
}
