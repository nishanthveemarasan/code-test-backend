<?php

namespace App\Observers;

use App\Models\Profile;
use App\Traits\CacheTrait;

class ProfileObserver
{
    use CacheTrait;
    /**
     * Handle the Profile "created" event.
     */
    public function created(Profile $profile): void
    {
        $this->forgetCaches(['home_page_data']);
    }

    /**
     * Handle the Profile "updated" event.
     */
    public function updated(Profile $profile): void
    {
        $this->forgetCaches(['home_page_data']);
    }

    /**
     * Handle the Profile "deleted" event.
     */
    public function deleted(Profile $profile): void
    {
        $this->forgetCaches(['home_page_data']);
    }

    /**
     * Handle the Profile "restored" event.
     */
    public function restored(Profile $profile): void
    {
        //
    }

    /**
     * Handle the Profile "force deleted" event.
     */
    public function forceDeleted(Profile $profile): void
    {
        //
    }
}
