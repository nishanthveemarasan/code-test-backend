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

    protected function authenticate()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        return $user;
    }

    /** @test */
    public function user_can_create_education()
    {
        $this->authenticate();

        $data = Education::factory()->make()->toArray();
        unset($data['user_id'], $data['uuid']);
        $response = $this->postJson(route('education.store'), $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('educations', [
            'course' => $data['course'],
            'institution' => $data['institution'],
        ]);
    }

    /** @test */
    public function create_fails_with_invalid_data()
    {
        $this->authenticate();

        $response = $this->postJson(route('education.store'), []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['from', 'course', 'institution']);
    }

    /** @test */
    public function user_can_update_own_education()
    {
        $user = $this->authenticate();

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

    /** @test */
    public function user_cannot_update_someone_else_education()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Passport::actingAs($user1);

        $education = Education::factory()->for($user2)->create();

        $response = $this->putJson(
            route('api.user.education.update', $education->uuid),
            ['course' => 'Hacked Course']
        );

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_delete_own_education()
    {
        $user = $this->authenticate();

        $education = Education::factory()->for($user)->create();

        $response = $this->deleteJson(
            route('api.user.education.destroy', $education->uuid)
        );

        $response->assertStatus(200);

        $this->assertDatabaseMissing('educations', [
            'uuid' => $education->uuid,
        ]);
    }

    /** @test */
    public function user_cannot_delete_someone_else_education()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Passport::actingAs($user1);

        $education = Education::factory()->for($user2)->create();

        $response = $this->deleteJson(
            route('api.user.education.destroy', $education->uuid)
        );

        $response->assertStatus(403);
    }
}
