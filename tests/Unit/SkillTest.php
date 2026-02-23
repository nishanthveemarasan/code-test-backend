<?php

namespace Tests\Untest;

use Tests\TestCase;
use App\Models\User;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SkillTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_can_create_a_skill()
    {
        $user = User::factory()->create();

        $skill = Skill::factory()
            ->for($user)  
            ->create([
                'name' => 'AutoCAD'
            ]);

        $this->assertDatabaseHas('skills', [
            'name' => 'AutoCAD',
            'user_id' => $user->id,
        ]);

        $this->assertNotNull($skill->uuid);
    }

    
    public function test_can_update_a_skill()
    {
        $skill = Skill::factory()->create([
            'name' => 'AutoCat'
        ]);

        $skill->update([
            'name' => 'AutoCAD'
        ]);

        $this->assertDatabaseHas('skills', [
            'id' => $skill->id,
            'name' => 'AutoCAD'
        ]);
    }

    
    public function test_can_delete_a_skill()
    {
        $skill = Skill::factory()->create();

        $skill->delete();

        $this->assertDatabaseMissing('skills', [
            'id' => $skill->id
        ]);
    }

    
    public function a_user_can_have_many_skills()
    {
        $user = User::factory()->create();

        Skill::factory()
            ->count(3)
            ->for($user)  // attach to the same user
            ->create();

        $this->assertCount(3, $user->skills);
        $this->assertInstanceOf(Skill::class, $user->skills->first());
    }

    
    public function a_skill_belongs_to_a_user()
    {
        $skill = Skill::factory()->create();

        $this->assertInstanceOf(User::class, $skill->user);
    }
}