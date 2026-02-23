<?php

namespace App\Services;

use App\Models\Experience;
use App\Models\User;

class ExperienceService
{    
    /**
     * Method createExperience
     *
     * @param User $user [explicite description]
     * @param array $data [explicite description]
     *
     * @return Experience
     */
    public function createExperience(array $data, User $user): Experience
    {
        return $user->experiences()->create($data);
    }
    
    /**
     * Method updateExperience
     *
     * @param User $user [explicite description]
     * @param Experience $experience [explicite description]
     * @param array $data [explicite description]
     *
     * @return Experience
     */
    public function updateExperience(array $data, User $user, Experience $experience): Experience
    {
        if ($experience->user_id !== $user->id) {
            abort(403);
        }

        $experience->update($data);

        return $experience;
    }
    
    /**
     * Method deleteExperience
     *
     * @param User $user [explicite description]
     * @param Experience $experience [explicite description]
     *
     * @return void
     */
    public function deleteExperience(User $user, Experience $experience): void
    {
        if ($experience->user_id !== $user->id) {
            abort(403);
        }

        $experience->delete();
    }
}
