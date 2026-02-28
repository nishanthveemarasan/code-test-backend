<?php

namespace App\Exceptions;

use Exception;

class DropboxUploadException extends Exception
{
    public static function uploadFailed(string $error): self
    {
        return new self("Failed to upload file to Dropbox at path: {$error}");
    }
}
