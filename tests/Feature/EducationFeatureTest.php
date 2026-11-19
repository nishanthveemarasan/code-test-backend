<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Education;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EducationFeatureTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_user_can_create_education()
    {
        $user = $this->authenticateUser();

        $data = Education::factory()->make()->toArray();
        unset($data['user_id'], $data['uuid']);
        $response = $this->postJson(route('education.store'), $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('educations', [
            'course' => $data['course'],
            'institution' => $data['institution'],
        ]);
    }

    
    public function test_create_fails_with_invalid_data()
    {
        $user = $this->authenticateUser();

        $response = $this->postJson(route('education.store'), []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['from', 'course', 'institution']);
    }

    
    public function test_user_can_update_own_education()
    {
        $user = $this->authenticateUser();

        $education = Education::factory()->for($user)->create();

        $payload = [
            'course' => 'Updated Course Name',
        ];

        $response = $this->putJson(
            route('api.user.education.update', $education->uuid),
            $payload
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('educations', [
            'uuid' => $education->uuid,
            'course' => 'Updated Course Name',
        ]);
    }

    
    public function test_user_cannot_update_someone_else_education()
    {
        $user1 = $this->authenticateUser();
        $user2 = User::factory()->create();

        $education = Education::factory()->for($user2)->create();

        $response = $this->putJson(
            route('api.user.education.update', $education->uuid),
            ['course' => 'Hacked Course']
        );

        $response->assertStatus(403);
    }

    
    public function test_user_can_delete_own_education()
    {
        $user = $this->authenticateUser();

        $education = Education::factory()->for($user)->create();

        $response = $this->deleteJson(
            route('api.user.education.destroy', $education->uuid)
        );

        $response->assertStatus(200);

        $this->assertDatabaseMissing('educations', [
            'uuid' => $education->uuid,
        ]);
    }

    
    public function test_user_cannot_delete_someone_else_education()
    {
        $user1 = $this->authenticateUser();
        $user2 = User::factory()->create();

        $education = Education::factory()->for($user2)->create();

        $response = $this->deleteJson(
            route('api.user.education.destroy', $education->uuid)
        );

        $response->assertStatus(403);
    }
}
