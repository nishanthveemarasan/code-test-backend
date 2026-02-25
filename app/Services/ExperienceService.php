<?php

namespace App\Services;

use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use App\Models\User;

class ExperienceService
{

    public function list(User $user)
    {
        $paginateList = $user->experiences()->orderBy('from', 'asc')->paginate(10);
        return ExperienceResource::collection($paginateList)->response()
            ->getData(true);
    }
    /**
     * Method createExperience
     *
     * @param User $user
     * @param array $data
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
     * @param User $user
     * @param Experience $experience
     * @param array $data
     *
     * @return Experience
     */
    public function updateExperience(array $data, User $user, Experience $experience): Experience
    {
        $experience->update($data);

        return $experience;
    }

    /**
     * Method deleteExperience
     *
     * @param User $user
     * @param Experience $experience
     *
     * @return void
     */
    public function deleteExperience(User $user, Experience $experience): void
    {
        $experience->delete();
    }
}
