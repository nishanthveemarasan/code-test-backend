<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'full_url' => $this->whenNotNull($this->full_url),
            'title' => $this->whenNotNull($this->title),
            'order' => $this->whenNotNull($this->order),
        ];
    }
}
