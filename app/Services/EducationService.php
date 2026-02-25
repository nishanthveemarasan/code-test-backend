<?php

namespace App\Services;

use App\Http\Resources\EducationResource;
use App\Models\Education;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EducationService
{   
    
    public function list(User $user)
    {
        $paginateList = $user->educations()->orderBy('from', 'asc')->paginate(10);
        return EducationResource::collection($paginateList)->response()
            ->getData(true);
    }

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