<?php

namespace App\Http\Resources;

use App\Http\Resources\FileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'first_name' => $this->whenNotNull($this->first_name),
            'last_name' => $this->whenNotNull($this->last_name),
            'email' => $this->whenNotNull($this->email),
            'phone' => $this->whenNotNull($this->phone),
            'address' => $this->whenNotNull($this->address),
            'biography' => $this->whenNotNull($this->biography),
            'bottom_line' => $this->whenNotNull($this->bottom_line),
            'image' => new FileResource($this->whenLoaded('file')),
        ];
    }
}
