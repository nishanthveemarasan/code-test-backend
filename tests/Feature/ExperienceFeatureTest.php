<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Experience;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ExperienceFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    
    public function test_user_can_create_experience()
    {
        $user = $this->authenticateUser();

        $data = Experience::factory()->make()->toArray();
        unset($data['user_id'], $data['uuid']);

        $response = $this->postJson(
            route('api.user.experience.store'),
            $data
        );

        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'data']);

        $this->assertDatabaseHas('experiences', [
            'user_id' => $user->id,
            'role' => $data['role']
        ]);
    }

    
    public function test_user_can_update_own_experience()
    {
        $user = $this->authenticateUser();

        $experience = Experience::factory()
                        ->for($user)
                        ->create();

        $response = $this->putJson(
            route('api.user.experience.update', $experience->uuid),
            [
                'role' => 'Senior Engineer',
                'company' => $experience->company,
                'from' => $experience->from,
                'to' => $experience->to,
                'description' => $experience->description,
            ]
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('experiences', [
            'uuid' => $experience->uuid,
            'role' => 'Senior Engineer'
        ]);
    }

    
    public function test_user_can_delete_own_experience()
    {
        $user = $this->authenticateUser();

        $experience = Experience::factory()
                        ->for($user)
                        ->create();

        $response = $this->deleteJson(
            route('api.user.experience.destroy', $experience->uuid)
        );

        $response->assertStatus(200);

        $this->assertDatabaseMissing('experiences', [
            'uuid' => $experience->uuid
        ]);
    }

    
    public function test_user_cannot_update_someone_else_experience()
    {
        $owner = User::factory()->create();
        $loggedUser = $this->authenticateUser();

        $experience = Experience::factory()
                        ->for($owner)
                        ->create();

        $response = $this->putJson(
            route('api.user.experience.update', $experience->uuid),
            [
                'role' => 'Updated Role',
                'company' => $experience->company,
                'from' => $experience->from,
                'to' => $experience->to,
                'description' => $experience->description,
            ]
        );

        $response->assertStatus(403);

        $this->assertDatabaseMissing('experiences', [
            'uuid' => $experience->uuid,
            'role' => 'Updated Role'
        ]);
    }

    
    public function test_user_cannot_delete_someone_else_experience()
    {
        $owner = User::factory()->create();
        $loggedUser = $this->authenticateUser();

        $experience = Experience::factory()
                        ->for($owner)
                        ->create();

        $response = $this->deleteJson(
            route('api.user.experience.destroy', $experience->uuid)
        );

        $response->assertStatus(403);

        $this->assertDatabaseHas('experiences', [
            'uuid' => $experience->uuid
        ]);
    }

    
    public function test_guest_cannot_access_experience_routes()
    {
        $experience = Experience::factory()->create();

        $this->postJson(route('api.user.experience.store'), [])
             ->assertStatus(401);

        $this->putJson(route('api.user.experience.update', $experience->uuid), [])
             ->assertStatus(401);

        $this->deleteJson(route('api.user.experience.delete', $experience->uuid))
             ->assertStatus(401);
    }
}
