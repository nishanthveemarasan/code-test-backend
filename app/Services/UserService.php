<?php

namespace App\Services;

use App\Models\User;

class UserService{
    public function store(array $data, User $user){
        $contactInfo = $user->contactInfo()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return $contactInfo;
    }
}