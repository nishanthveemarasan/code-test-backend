<?php
use App\Models\User;
use App\Models\Testimonial;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialFeatureTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_user_can_create_testimonial()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $payload = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'start' => 5,
            'title' => 'Amazing Service',
            'content' => 'They delivered excellent results!'
        ];

        $response = $this->postJson(route('api.user.testimonial.store'), $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('testimonials', [
            'first_name' => 'John',
            'user_id' => $user->id
        ]);
    }

    
    public function test_user_can_update_own_testimonial()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $testimonial = Testimonial::factory()->for($user)->create([
            'title' => 'Old Title'
        ]);

        $payload = ['title' => 'New Title'];

        $response = $this->putJson(
            route('api.user.testimonial.update', $testimonial->uuid),
            $payload
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('testimonials', [
            'uuid' => $testimonial->uuid,
            'title' => 'New Title'
        ]);
    }

    
    public function test_user_cannot_update_someone_elses_testimonial()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Passport::actingAs($user1);

        $testimonial = Testimonial::factory()->for($user2)->create();

        $response = $this->putJson(
            route('api.user.testimonial.update', $testimonial->uuid),
            ['title' => 'Hacked Title']
        );

        $response->assertStatus(403);
    }

    
    public function test_user_can_delete_own_testimonial()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $testimonial = Testimonial::factory()->for($user)->create();

        $response = $this->deleteJson(
            route('api.user.testimonial.delete', $testimonial->uuid)
        );

        $response->assertStatus(200);

        $this->assertDatabaseMissing('testimonials', [
            'uuid' => $testimonial->uuid
        ]);
    }

    
    public function test_user_cannot_delete_someone_elses_testimonial()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Passport::actingAs($user1);

        $testimonial = Testimonial::factory()->for($user2)->create();

        $response = $this->deleteJson(
            route('api.user.testimonial.delete', $testimonial->uuid)
        );

        $response->assertStatus(403);
    }
}