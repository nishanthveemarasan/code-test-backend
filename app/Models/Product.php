<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes,HasUuids;

    public function casts()
    {
        return [
            'placements' => 'array',
            'personalizable' => 'boolean'
        ];
    }

    public function images(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
