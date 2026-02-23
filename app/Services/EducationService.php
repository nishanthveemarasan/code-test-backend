<?php

namespace App\Services;

use App\Models\Education;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EducationService
{    
    /**
     * Method store
     *
     * @param array $data
     * @param User $user
     *
     * @return Education
     */
    public function store(array $data, User $user): Education
    {
        return $user->educations()->create($data);
    }
    
    /**
     * Method update
     *
     * @param array $data
     * @param Education $education
     *
     * @return Education
     */
    public function update(array $data, Education $education): Education
    {
        $education->update($data);

        return $education;
    }
    
    /**
     * Method delete
     *
     * @param Education $education
     *
     * @return void
     */
    public function delete(Education $education): void
    {
        $education->delete();
    }
}