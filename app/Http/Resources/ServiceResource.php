<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->whenNotNull($this->uuid),
            'title' => $this->title,
            'description' => $this->whenNotNull($this->description),
            'points' => $this->whenNotNull($this->points),
            'special_point' => $this->whenNotNull($this->special_point)
        ];
    }
}
