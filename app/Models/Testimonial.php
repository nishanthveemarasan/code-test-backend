<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Testimonial extends Model
{
    /** @use HasFactory<\Database\Factories\TestimonialFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'first_name',
        'last_name',
        'star',
        'title',
        'content'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($testimonial) {
            $testimonial->uuid = Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
