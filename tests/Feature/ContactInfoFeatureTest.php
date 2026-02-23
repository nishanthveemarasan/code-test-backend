<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;

class ContactInfoFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_store_contact_info()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $faker = Faker::create();

        $response = $this->postJson(
            route('api.user.contact-info'),  // Named route
            [
                'phone' => $faker->phoneNumber,
                'email' => $faker->safeEmail,
                'address' => $faker->address,
            ]
        );

        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'data']);

        $this->assertDatabaseHas('contact_infos', [
            'user_id' => $user->id,
            'email' => $response->json('data.email') // assert the saved email
        ]);
    }

    /** @test */
    public function invalid_data_returns_validation_error()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->postJson(
            route('api.user.contact-info'),
            [
                'phone' => '',                  // invalid
                'email' => 'not-an-email',      // invalid
                'address' => '',                // invalid
            ]
        );

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['phone', 'email', 'address']);
    }

    /** @test */
    public function guest_cannot_access_contact_info_route()
    {
        $response = $this->postJson(route('api.user.contact-info'), []);

        $response->assertStatus(401); // Unauthorized
    }
}