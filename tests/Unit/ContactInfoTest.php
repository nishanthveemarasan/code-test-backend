<?php

namespace Tests\Unit;

use App\Models\ContactInfo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Faker\Factory as Faker;

class ContactInfoTest extends TestCase
{
    use RefreshDatabase;
    public function test_can_create_contact_info()
    {
        $user = User::factory()->create();
        $faker = Faker::create();
        
        $contact = ContactInfo::create([
            'user_id' => $user->id,
            'phone' => $faker->phoneNumber,
            'email' => 'test@example.com',
            'address' => $faker->address,
        ]);

        $this->assertDatabaseHas('contact_infos', [
            'email' => 'test@example.com'
        ]);
    }

    public function test_can_update_contact_info()
    {
        $user = User::factory()->create();
        $user = User::factory()->create();
        $contact = ContactInfo::factory()
                    ->for($user)
                    ->create();

        $contact->update(['phone' => '999999999']);

        $this->assertEquals('999999999', $contact->fresh()->phone);
    }

    public function test_can_delete_contact_info()
    {
        $contact = ContactInfo::factory()->create();

        $contact->delete();

        $this->assertDatabaseMissing('contact_infos', [
            'id' => $contact->id
        ]);
    }

    public function test_user_has_contact_info_relationship()
    {
        $user = User::factory()->create();
        $contact = ContactInfo::factory()
                    ->for($user)
                    ->create();

        $this->assertInstanceOf(
            ContactInfo::class,
            $user->contactInfo
        );
    }
}
