<?php

namespace App\Services;

use App\Enums\SkillAction;

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
            $action = SkillAction::tryFrom($skill['action']);
            switch ($action) {
                case SkillAction::ADD:
                    $user->skills()->firstOrCreate([
                        'name' => $skill['name']
                    ]);
                    break;
                case SkillAction::DELETE:
                    $skillToDelete = $user->skills()
                        ->where('uuid', $skill['uuid'])
                        ->first();
                    if ($skillToDelete) {
                        $skillToDelete->delete();
                    }
                    break;
                default:
                    break;
            }
            
        }
    }
}
