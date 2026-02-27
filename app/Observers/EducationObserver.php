<?php

namespace App\Observers;

use App\Models\Education;
use App\Traits\CacheTrait;

class EducationObserver
{
    use CacheTrait;
    /**
     * Handle the Education "created" event.
     */
    public function created(Education $education): void
    {
        $this->forgetCaches(['about_page_data']);
    }

    /**
     * Handle the Education "updated" event.
     */
    public function updated(Education $education): void
    {
        $this->forgetCaches(['about_page_data']);
    }

    /**
     * Handle the Education "deleted" event.
     */
    public function deleted(Education $education): void
    {
        $this->forgetCaches(['about_page_data']);
    }

    /**
     * Handle the Education "restored" event.
     */
    public function restored(Education $education): void
    {
        //
    }

    /**
     * Handle the Education "force deleted" event.
     */
    public function forceDeleted(Education $education): void
    {
        //
    }
}
