<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'first_name',
        'last_name',
        'biography',
        'bottom_line',
        'email',
        'phone',
        'address'
    ];

    protected $with = ['file'];

    protected static function booted()
    {
        static::creating(function ($profile) {
            if (!$profile->uuid) {
                $profile->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    // User relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
