<?php

namespace App\Models;

use App\Observers\ProfileObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[ObservedBy([ProfileObserver::class])]
class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'first_name',
        'last_name',
        'qualification',
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
