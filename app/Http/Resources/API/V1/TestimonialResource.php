<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;

class TestimonialResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->position,
            'company' => $this->company,
            'content' => $this->content,
            'rating' => $this->rating,
            'image_url' => $this->image_url,
            'status' => $this->status,
        ] + $this->formatTimestamps();
    }
}