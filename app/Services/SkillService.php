<?php

namespace App\Services;

class SkillService
{
    public function get($user)
    {
        $skills = $user->skills()->get();
        return $skills->toResourceCollection(); 
    }
    public function updateSkills($user, $skills)
    {
        foreach ($skills as $skill) {
            if ($skill['action'] === 'add') {
                $skill = $user->skills()->firstOrCreate([
                    'name' => $skill['name']
                ]);
            } elseif ($skill['action'] === 'delete') {
                $skill = $user->skills()
                    ->where('name', $skill['name'])
                    ->first();
                if ($skill) {
                    $skill->delete();
                }
            }
        }
    }
}
