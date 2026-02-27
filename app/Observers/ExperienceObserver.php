<?php

namespace App\Observers;

use App\Models\Experience;
use App\Traits\CacheTrait;

class ExperienceObserver
{
    use CacheTrait;
    /**
     * Handle the Experience "created" event.
     */
    public function created(Experience $experience): void
    {
        $this->forgetCaches(['about_page_data']);
    }

    /**
     * Handle the Experience "updated" event.
     */
    public function updated(Experience $experience): void
    {
        $this->forgetCaches(['about_page_data']);
    }

    /**
     * Handle the Experience "deleted" event.
     */
    public function deleted(Experience $experience): void
    {
        $this->forgetCaches(['about_page_data']);
    }

    /**
     * Handle the Experience "restored" event.
     */
    public function restored(Experience $experience): void
    {
        //
    }

    /**
     * Handle the Experience "force deleted" event.
     */
    public function forceDeleted(Experience $experience): void
    {
        //
    }
}
