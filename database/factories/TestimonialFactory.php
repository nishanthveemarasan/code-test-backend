<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'user_id' => User::factory(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'star' => $this->faker->numberBetween(1, 5),
            'title' => $this->faker->sentence(3),
            'content' => $this->faker->paragraph(),
        ];
    }
}
