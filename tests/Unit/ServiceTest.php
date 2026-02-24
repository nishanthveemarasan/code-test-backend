<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_service()
    {
        $service = Service::factory()->create();

        $this->assertDatabaseHas('services', [
            'id' => $service->id
        ]);
    }

    public function test_update_service()
    {
        $service = Service::factory()->create();

        $service->update([
            'title' => 'Updated Service Title'
        ]);

        $this->assertEquals(
            'Updated Service Title',
            $service->fresh()->title
        );
    }

    public function test_delete_service()
    {
        $service = Service::factory()->create();

        $service->delete();

        $this->assertDatabaseMissing('services', [
            'id' => $service->id
        ]);
    }

    public function test_user_has_many_services()
    {
        $user = User::factory()->create();

        Service::factory()
            ->count(3)
            ->for($user)
            ->create();

        $this->assertCount(3, $user->services);
    }

    public function test_service_belongs_to_user()
    {
        $user = User::factory()->create();

        $service = Service::factory()
            ->for($user)
            ->create();

        $this->assertEquals(
            $user->id,
            $service->user->id
        );
    }

    public function test_service_title_must_be_unique_per_user()
    {
        $user = User::factory()->create();

        Service::factory()->for($user)->create([
            'title' => 'Infrastructure Development'
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Service::factory()->for($user)->create([
            'title' => 'Infrastructure Development'
        ]);
    }

    public function test_different_users_can_have_same_title()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Service::factory()->for($user1)->create([
            'title' => 'Infrastructure Development'
        ]);

        Service::factory()->for($user2)->create([
            'title' => 'Infrastructure Development'
        ]);

        $this->assertDatabaseCount('services', 2);
    }
}