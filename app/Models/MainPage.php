<?php

namespace App\Models;

use App\Http\Resources\HomepageResource;
use App\Observers\MainPageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
#[UseResource(HomepageResource::class)]
#[ObservedBy([MainPageObserver::class])]
class MainPage extends Model
{
    /** @use HasFactory<\Database\Factories\MainPageFactory> */
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'title',
        'description',
    ];

    protected $with = ['files'];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public static function booted()
    {
        static::creating(function ($mainPage) {
            if (!$mainPage->uuid) {
                $mainPage->uuid = (string) Str::uuid();
            }
        });
    }

    public function title(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => Str::title($value),
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    

}
