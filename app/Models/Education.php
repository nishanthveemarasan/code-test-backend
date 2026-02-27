<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Education extends Model
{
    /** @use HasFactory<\Database\Factories\EducationFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'from',
        'to',
        'course',
        'institution',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($education) {
            $education->uuid = Str::uuid();
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

    protected function casts()
    {
        return [
            'from' => 'integer',
            'to' => 'integer',
        ];
    }
}
