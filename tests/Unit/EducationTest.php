<?php

namespace Tests\Unit;

use App\Models\Education;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EducationTest extends TestCase
{
    use RefreshDatabase;
    public function test_create_education()
    {
        $education = Education::factory()->create();

        $this->assertDatabaseHas('educations', [
            'uuid' => $education->uuid
        ]);
    }

    public function test_update_education()
    {
        $education = Education::factory()->create();

        $education->update([
            'course' => 'Updated Course'
        ]);

        $this->assertEquals('Updated Course', $education->fresh()->course);
    }

    public function test_delete_education()
    {
        $education = Education::factory()->create();

        $education->delete();

        $this->assertDatabaseMissing('educations', [
            'id' => $education->id
        ]);
    }

    public function test_user_has_many_educations()
    {
        $user = User::factory()->create();

        Education::factory()->count(3)->for($user)->create();

        $this->assertCount(3, $user->educations);
    }
}
