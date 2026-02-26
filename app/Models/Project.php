<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'type',
        'city',
    ];

    protected $with = ['file'];

    public static function booted()
    {
        static::creating(function ($project) {
            if (!$project->uuid) {
                $project->uuid = (string) Str::uuid();
            }
        });
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => Str::title($value),
        );
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }
}
