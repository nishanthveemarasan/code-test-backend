<?php

namespace App\Observers;

use App\Models\Skill;
use App\Traits\CacheTrait;

class SkillObserver
{
    use CacheTrait;
    /**
     * Handle the Skill "created" event.
     */
    public function created(Skill $skill): void
    {
        $this->forgetCaches(['about_page_data']);
    }

    /**
     * Handle the Skill "updated" event.
     */
    public function updated(Skill $skill): void
    {
        $this->forgetCaches(['about_page_data']);
    }

    /**
     * Handle the Skill "deleted" event.
     */
    public function deleted(Skill $skill): void
    {
        $this->forgetCaches(['about_page_data']);
    }

    /**
     * Handle the Skill "restored" event.
     */
    public function restored(Skill $skill): void
    {
        //
    }

    /**
     * Handle the Skill "force deleted" event.
     */
    public function forceDeleted(Skill $skill): void
    {
        //
    }
}
