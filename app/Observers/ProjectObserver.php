<?php

namespace App\Observers;

use App\Models\Project;
use App\Traits\CacheTrait;

class ProjectObserver
{
    use CacheTrait;
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        $this->forgetCaches(['home_page_data']);
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        $this->forgetCaches(['home_page_data']);
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        $this->forgetCaches(['home_page_data']);
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
