<?php

namespace App\Models;

use App\ContactUsStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'staus'
    ];

    protected function casts(): array
    {
        return [
            'staus' => ContactUsStatus::class,
        ];
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }
}
