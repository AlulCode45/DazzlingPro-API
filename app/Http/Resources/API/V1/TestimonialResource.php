<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class TestimonialResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        $imageUrl = $this->image_url;

        // Convert relative path to full URL
        if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
            $imageUrl = url('/storage/' . $imageUrl);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $this->role,
            'content' => $this->content,
            'rating' => $this->rating,
            'image_url' => $imageUrl,
        ] + $this->formatTimestamps();
    }
}