<?php
use App\Models\User;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_can_create_testimonial()
    {
        $user = User::factory()->create();

        $testimonial = Testimonial::factory()
            ->for($user)
            ->create([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'start' => 5,
            ]);

        $this->assertDatabaseHas('testimonials', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'user_id' => $user->id,
        ]);
    }

    
    public function test_can_update_testimonial()
    {
        $testimonial = Testimonial::factory()->create([
            'start' => 3,
        ]);

        $testimonial->update(['start' => 4]);

        $this->assertEquals(4, $testimonial->fresh()->start);
    }

    
    public function test_can_delete_testimonial()
    {
        $testimonial = Testimonial::factory()->create();

        $testimonial->delete();

        $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
    }

    
    public function a_user_can_have_many_testimonials()
    {
        $user = User::factory()->create();

        Testimonial::factory()->count(3)
            ->for($user)
            ->create();

        $this->assertCount(3, $user->testimonials);
    }

    
    public function a_testimonial_belongs_to_a_user()
    {
        $testimonial = Testimonial::factory()->create();

        $this->assertInstanceOf(User::class, $testimonial->user);
    }
}