<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Experience;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExperienceTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_can_create_an_experience()
    {
        $user = User::factory()->create();

        $experience = Experience::factory()
                        ->for($user)
                        ->create();

        $this->assertDatabaseHas('experiences', [
            'uuid' => $experience->uuid,
            'user_id' => $user->id
        ]);

        $this->assertNotNull($experience->uuid);
    }

    
    public function test_can_update_an_experience()
    {
        $experience = Experience::factory()->create();

        $experience->update([
            'role' => 'Senior Engineer'
        ]);

        $this->assertDatabaseHas('experiences', [
            'uuid' => $experience->uuid,
            'role' => 'Senior Engineer'
        ]);
    }

    
    public function test_can_delete_an_experience()
    {
        $experience = Experience::factory()->create();

        $uuid = $experience->uuid;

        $experience->delete();

        $this->assertDatabaseMissing('experiences', [
            'uuid' => $uuid
        ]);
    }

    
    public function experience_belongs_to_user()
    {
        $user = User::factory()->create();

        $experience = Experience::factory()
                        ->for($user)
                        ->create();

        $this->assertInstanceOf(User::class, $experience->user);
        $this->assertEquals($user->id, $experience->user->id);
    }

    
    public function user_has_many_experiences()
    {
        $user = User::factory()->create();

        Experience::factory()
            ->count(3)
            ->for($user)
            ->create();

        $this->assertCount(3, $user->experiences);
    }
}
