<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Skill;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SkillFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_and_delete_skills()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        // Create a pre-existing skill to delete
        $existingSkill = Skill::factory()
            ->for($user)
            ->create(['name' => 'Survey']);

        $payload = [
            'skills' => [
                ['name' => 'AutoCAD', 'action' => 'add'],
                ['name' => 'Survey', 'action' => 'delete'],
            ]
        ];

        $response = $this->postJson(route('api.user.skill.store'), $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     ['name' => 'AutoCAD', 'status' => 'added'],
                     ['name' => 'Survey', 'status' => 'deleted'],
                 ]);

        $this->assertDatabaseHas('skills', [
            'name' => 'AutoCAD',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseMissing('skills', [
            'name' => 'Survey',
            'user_id' => $user->id,
        ]);
    }

    public function test_adding_existing_skill_does_not_duplicate()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $existingSkill = Skill::factory()
            ->for($user)
            ->create(['name' => 'AutoCAD']);

        $payload = [
            'skills' => [
                ['name' => 'AutoCAD', 'action' => 'add'],
            ]
        ];

        $response = $this->postJson(route('api.user.skill.store'), $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     ['name' => 'AutoCAD', 'status' => 'added'],
                 ]);

        $this->assertDatabaseCount('skills', 1);
    }

    public function test_deleting_non_existing_skill_returns_not_found()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $payload = [
            'skills' => [
                ['name' => 'NonExistent', 'action' => 'delete'],
            ]
        ];

        $response = $this->postJson(route('api.user.skill.store'), $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     ['name' => 'NonExistent', 'status' => 'not_found'],
                 ]);
    }

    public function test_invalid_action_returns_validation_error()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $payload = [
            'skills' => [
                ['name' => 'AutoCAD', 'action' => 'invalid_action'],
            ]
        ];

        $response = $this->postJson(route('api.user.skill.store'), $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['skills.0.action']);
    }

    public function test_unauthenticated_user_cannot_manage_skills()
    {
        $payload = [
            'skills' => [
                ['name' => 'AutoCAD', 'action' => 'add'],
            ]
        ];

        $response = $this->postJson(route('api.user.skill.store'), $payload);

        $response->assertStatus(401);
    }
}