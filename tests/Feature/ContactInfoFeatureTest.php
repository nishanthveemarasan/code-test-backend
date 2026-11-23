<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ContactInfoFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    
    public function test_authenticated_user_can_store_contact_info()
    {
        $user = $this->authenticateUser();

        $response = $this->postJson(
            route('api.user.contact-info'),  // Named route
            [
                'phone' => $this->faker->phoneNumber,
                'email' => $this->faker->safeEmail,
                'address' => $this->faker->address,
            ]
        );

        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'data']);

        $this->assertDatabaseHas('contact_infos', [
            'user_id' => $user->id,
            'email' => $response->json('data.email') // assert the saved email
        ]);
    }


    public function test_invalid_data_returns_validation_error()
    {
        $user = $this->authenticateUser();

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

    public function test_guest_cannot_access_contact_info_route()
    {
        $response = $this->postJson(route('api.user.contact-info'), []);

        $response->assertStatus(401); // Unauthorized
    }
}
