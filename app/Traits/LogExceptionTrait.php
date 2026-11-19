<?php
namespace App\Traits;

use App\Jobs\NotifyOwnerErrorEmailJob;
use Exception;

use Illuminate\Support\Facades\Log;

trait LogExceptionTrait
{
    /**
     * Clear specific cache keys.
     * * @param array $keys
     */
    public function logException(string $title, Exception $e, array $data=[], bool $emailNotify = false, string $logChannel="exception"): void
    {
        $error = "{$title} :". $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine();
        Log::channel($logChannel)->error("{$title} : {$error}");
        if($emailNotify){
            NotifyOwnerErrorEmailJob::dispatch($data, $error);
        }
    }
}