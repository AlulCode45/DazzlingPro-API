<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    protected $table = "portfolios";

    protected $fillable = [
        "title",
        "slug",
        "description",
        "short_description",
        "client_name",
        "event_date",
        "event_location",
        "portfolio_category_id",
        "images",
        "featured_image",
        "featured",
        "completed",
        "status",
        "sort_order"
    ];

    protected $casts = [
        "event_date" => "date",
        "featured" => "boolean",
        "completed" => "boolean",
        "status" => "boolean"
    ];

    protected function images(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    $images = is_array($decoded) ? $decoded : [];
                } elseif (is_array($value)) {
                    $images = $value;
                } else {
                    return [];
                }
                return array_map(function ($image) {
                    if (empty($image))
                        return null;
                    if (str_starts_with($image, "http://") || str_starts_with($image, "https://")) {
                        return $image;
                    }
                    return url("/storage/" . $image);
                }, $images);
            },
            set: fn($value) => json_encode(is_array($value) ? $value : [])
        );
    }

    protected function featuredImage(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (empty($value))
                    return null;
                if (str_starts_with($value, "http://") || str_starts_with($value, "https://")) {
                    return $value;
                }
                return url("/storage/" . $value);
            },
            set: fn($value) => $value
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PortfolioCategory::class, "portfolio_category_id");
    }
}

