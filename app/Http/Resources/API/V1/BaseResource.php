<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource
{
    protected function formatDate($date)
    {
        return $date ? $date->format('Y-m-d H:i:s') : null;
    }

    protected function formatTimestamps()
    {
        return [
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
        ];
    }
}