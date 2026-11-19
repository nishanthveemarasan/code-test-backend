<?php

use App\Models\User;
use App\Models\Service;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Faker\Factory as Faker;

class ServiceFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_service()
    {
        $user = $this->authenticateUser();

        $faker = Faker::create();

        $payload = [
            'title' =>$faker->sentence(3),
            'description' =>$faker->paragraph(),
            'points' => [
                $faker->sentence(2),
               $faker->sentence(2),
               $faker->sentence(2),
            ],
            'special_point' =>$faker->sentence(5),
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
        $user = $this->authenticateUser();

        Service::factory()->for($user)->create([
            'title' => 'Infrastructure Development'
        ]);

        $faker = Faker::create();

        $response = $this->postJson(
            route('api.user.service.store'),
            [
                'title' => 'Infrastructure Development',
                'description' => $faker->paragraph(),
                'points' => ['A'],
                'special_point' => $faker->sentence(),
            ]
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_user_can_update_own_service()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $faker = Faker::create();

        $service = Service::factory()
            ->for($user)
            ->create([
                'title' => 'Old Title'
            ]);

        $payload = [
            'title' => 'New Title',
            'description' => $faker->paragraph(),
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
        $user1 = $this->authenticateUser();
        $user2 = User::factory()->create();
       
        $service = Service::factory()
            ->for($user2)
            ->create();

        $response = $this->putJson(
            route('api.user.service.update', $service->uuid),
            ['title' => 'updated Title']
        );

        $response->assertStatus(403);
    }

    public function test_user_cannot_update_to_duplicate_title()
    {
        $user = $this->authenticateUser();

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
        $user2 = $this->authenticateUser();

        $faker = Faker::create();

        Service::factory()->for($user1)->create([
            'title' => 'Infrastructure Development'
        ]);

        $response = $this->postJson(
            route('api.user.service.store'),
            [
                'title' => 'Infrastructure Development',
                'description' => $faker->paragraph(),
                'points' => ['A'],
                'special_point' => $faker->sentence(),
            ]
        );

        $response->assertStatus(201);
    }

    public function test_user_can_delete_own_service()
    {
        $user = $this->authenticateUser();

        $service = Service::factory()
            ->for($user)
            ->create();

        $response = $this->deleteJson(
            route('api.user.service.destroy', $service->uuid)
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
            route('api.user.service.destroy', $service->uuid)
        );

        $response->assertStatus(403);
    }
}