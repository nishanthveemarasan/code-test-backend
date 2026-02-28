<?php

namespace App\Models;

use Database\Factories\DropboxCredentialFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UseFactory(DropboxCredentialFactory::class)]
class DropboxCredential extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'code',
        'client_id',
        'client_secret',
        'app_redirect_url',
        'refresh_token',
        'access_token'
    ];
}
