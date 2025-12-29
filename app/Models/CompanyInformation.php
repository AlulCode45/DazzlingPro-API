<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInformation extends Model
{
    protected $fillable = [
        'company_name',
        'tagline',
        'description',
        'email',
        'phone',
        'whatsapp',
        'address',
        'city',
        'province',
        'postal_code',
        'country',
        'website_url',
        'logo_url',
        'favicon_url',
        'social_media',
        'operating_hours',
        'google_maps_url',
        'business_registration_number',
        'tax_id',
        'bank_account',
        'bank_name',
        'about_us',
        'mission',
        'vision',
        'core_values',
        'seo_meta',
        'is_active',
    ];

    protected $casts = [
        'social_media' => 'array',
        'operating_hours' => 'array',
        'seo_meta' => 'array',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'social_media' => '[]',
        'operating_hours' => '[]',
        'seo_meta' => '[]',
    ];
}
