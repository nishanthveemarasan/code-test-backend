<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class File extends Model
{
    /** @use HasFactory<\Database\Factories\FileFactory> */
    use HasFactory;

    protected $fillable = ['path', 'mime_type', 'fileable_id', 'fileable_type', 'title', 'order'];

    protected static function booted()
    {
        static::creating(function ($profile) {
            if (!$profile->uuid) {
                $profile->uuid = (string) Str::uuid();
            }
        });
    }

    protected $appends = ['full_url'];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected function fullUrl(): Attribute
    {
        return new Attribute(
            get: function () {

                if(Str::startsWith($this->mime_type, 'image')) {
                    return config('app.url').Storage::url($this->path);
                }
                return null;
            },
        );
    }

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
