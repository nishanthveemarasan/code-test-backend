<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid'        => $this->whenNotNull($this->uuid),
            'company'     => $this->company,
            'role'        => $this->role,
            'from' => $this->from,
            'to'   => $this->to,
            'description' => $this->description
        ];
    }
}
