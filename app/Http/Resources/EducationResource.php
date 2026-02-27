<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationResource extends JsonResource
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
            'course'     => $this->course,
            'institution'        => $this->institution,
            'from' => $this->from,
            'to'   => $this->to ?? 'Present',
            'description' => $this->description
        ];;
    }
}
