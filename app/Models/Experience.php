<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Experience extends Model
{
    /** @use HasFactory<\Database\Factories\ExperienceFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'uuid',
        'user_id',
        'from',
        'to',
        'role',
        'company',
        'description',
    ];

    protected static function booted()
    {
        static::creating(function ($experience) {
            if (!$experience->uuid) {
                $experience->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
{
    return 'uuid';
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
