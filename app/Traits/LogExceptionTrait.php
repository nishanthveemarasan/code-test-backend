<?php
namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;

trait LogExceptionTrait{
    public function logException(Exception $error, $channel="exception"){
        Log::channel($channel)->error($error->getMessage()." ".$error->getFile()." ".$error->getLine());
    }
}