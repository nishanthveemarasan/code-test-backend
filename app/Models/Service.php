<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'title',
        'description',
        'points',
        'special_point',
    ];

    protected static function booted()
    {
        static::creating(function ($experience) {
            if (!$experience->uuid) {
                $experience->uuid = (string) Str::uuid();
            }
        });
    }
    
    protected function title(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Str::title($value),
        );
    }
    /**
     * Method casts
     *
     * @return array
     */
    public function casts() : array
    {
        return [
            'points' => 'array',
        ];
    }
    
    /**
     * Method getRouteKeyName
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
