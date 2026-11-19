<?php

namespace Tests\Feature;

use App\Events\NotifyOwnerEvent;
use App\Events\NotifyUserEvent;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ContactFormFeatureTest extends TestCase
{
    use RefreshDatabase;
    public function test_store_contact_form_successfully()
    {
       $this->authenticateClient(); // Authenticate as a client

        Event::fake([
            NotifyOwnerEvent::class,
            NotifyUserEvent::class,
        ]);

        $faker = Faker::create();

        $data = [
            'name' => $faker->name,
            'phone' => '+77423432423',
            'email' => $faker->safeEmail,
            'address' => $faker->address,
            'subject' => $faker->sentence,
            'message' => $faker->paragraph,
        ];

        $response = $this->postJson(
            route('client.contact.store'),  // Named route
            $data
        );

        
        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'data']);

        $this->assertDatabaseHas('contact_us', [
            'email' => $data['email'] 
        ]);

         // Assert owner notification event dispatched
         Event::assertDispatched(NotifyOwnerEvent::class);

         // Assert user notification event dispatched
         Event::assertDispatched(NotifyUserEvent::class);
     }


}
