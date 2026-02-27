<?php

namespace App\Observers;

use App\Models\Service;
use App\Traits\CacheTrait;

class ServiceObserver
{
    use CacheTrait;
    /**
     * Handle the Service "created" event.
     */
    public function created(Service $service): void
    {
        $this->forgetCaches(['home_page_data', 'about_page_data']);
    }

    /**
     * Handle the Service "updated" event.
     */
    public function updated(Service $service): void
    {
        $this->forgetCaches(['home_page_data', 'about_page_data']);
    }

    /**
     * Handle the Service "deleted" event.
     */
    public function deleted(Service $service): void
    {
        $this->forgetCaches(['home_page_data', 'about_page_data']);
    }

    /**
     * Handle the Service "restored" event.
     */
    public function restored(Service $service): void
    {
        //
    }

    /**
     * Handle the Service "force deleted" event.
     */
    public function forceDeleted(Service $service): void
    {
        //
    }
}
