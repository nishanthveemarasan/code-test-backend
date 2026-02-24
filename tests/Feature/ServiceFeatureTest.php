<?php

use App\Models\User;
use App\Models\Service;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_service()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $payload = [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'points' => [
                fake()->sentence(2),
                fake()->sentence(2),
                fake()->sentence(2),
            ],
            'special_point' => fake()->sentence(5),
        ];

        $response = $this->postJson(
            route('api.user.service.store'),
            $payload
        );

        $response->assertStatus(201);

        $this->assertDatabaseHas('services', [
            'title' => $payload['title'],
            'user_id' => $user->id,
        ]);
    }

    public function test_user_cannot_create_duplicate_title()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        Service::factory()->for($user)->create([
            'title' => 'Infrastructure Development'
        ]);

        $response = $this->postJson(
            route('api.user.service.store'),
            [
                'title' => 'Infrastructure Development',
                'description' => fake()->paragraph(),
                'points' => ['A'],
                'special_point' => fake()->sentence(),
            ]
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_user_can_update_own_service()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $service = Service::factory()
            ->for($user)
            ->create([
                'title' => 'Old Title'
            ]);

        $payload = [
            'title' => 'New Title',
            'description' => fake()->paragraph(),
            'points' => ['Point A', 'Point B'],
            'special_point' => 'Updated Special'
        ];

        $response = $this->putJson(
            route('api.user.service.update', $service->uuid),
            $payload
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('services', [
            'uuid' => $service->uuid,
            'title' => 'New Title'
        ]);
    }

    public function test_user_cannot_update_someone_elses_service()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Passport::actingAs($user1);

        $service = Service::factory()
            ->for($user2)
            ->create();

        $response = $this->putJson(
            route('api.user.service.update', $service->uuid),
            ['title' => 'Hacked Title']
        );

        $response->assertStatus(403);
    }

    public function test_user_cannot_update_to_duplicate_title()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        Service::factory()->for($user)->create([
            'title' => 'Service One'
        ]);

        $serviceTwo = Service::factory()->for($user)->create([
            'title' => 'Service Two'
        ]);

        $response = $this->putJson(
            route('api.user.service.update', $serviceTwo->uuid),
            [
                'title' => 'Service One',
                'description' => 'Updated',
                'points' => ['Updated'],
                'special_point' => 'Updated',
            ]
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_different_users_can_create_same_title()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Service::factory()->for($user1)->create([
            'title' => 'Infrastructure Development'
        ]);

        Passport::actingAs($user2);

        $response = $this->postJson(
            route('api.user.service.store'),
            [
                'title' => 'Infrastructure Development',
                'description' => fake()->paragraph(),
                'points' => ['A'],
                'special_point' => fake()->sentence(),
            ]
        );

        $response->assertStatus(201);
    }

    public function test_user_can_delete_own_service()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $service = Service::factory()
            ->for($user)
            ->create();

        $response = $this->deleteJson(
            route('api.user.service.delete', $service->uuid)
        );

        $response->assertStatus(200);

        $this->assertDatabaseMissing('services', [
            'uuid' => $service->uuid
        ]);
    }

    public function test_user_cannot_delete_someone_elses_service()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Passport::actingAs($user1);

        $service = Service::factory()
            ->for($user2)
            ->create();

        $response = $this->deleteJson(
            route('api.user.service.delete', $service->uuid)
        );

        $response->assertStatus(403);
    }
}