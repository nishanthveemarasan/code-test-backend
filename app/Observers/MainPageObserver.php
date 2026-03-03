<?php

namespace App\Observers;

use App\Models\MainPage;
use App\Traits\CacheTrait;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class MainPageObserver implements ShouldHandleEventsAfterCommit
{
    use CacheTrait;
    /**
     * Handle the MainPage "created" event.
     */
    public function created(MainPage $mainPage): void
    {
        $this->forgetCaches(['home_page_data']);
    }

    /**
     * Handle the MainPage "updated" event.
     */
    public function updated(MainPage $mainPage): void
    {
        $this->forgetCaches(['home_page_data']);
    }

    /**
     * Handle the MainPage "deleted" event.
     */
    public function deleted(MainPage $mainPage): void
    {
        $this->forgetCaches(['home_page_data']);
    }

    /**
     * Handle the MainPage "restored" event.
     */
    public function restored(MainPage $mainPage): void
    {
        //
    }

    /**
     * Handle the MainPage "force deleted" event.
     */
    public function forceDeleted(MainPage $mainPage): void
    {
        //
    }
}
