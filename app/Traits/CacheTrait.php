<?php
namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheTrait
{
    /**
     * Clear specific cache keys.
     * * @param array $keys
     */
    public function forgetCaches(array $keys): void
    {
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}