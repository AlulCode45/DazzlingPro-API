<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    protected $table = 'analytics';

    protected $fillable = [
        'event_type',
        'page_url',
        'page_title',
        'referrer',
        'user_agent',
        'ip_address',
        'session_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
