<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fromYear = $this->faker->numberBetween(2000, 2018);

        return [
            'uuid' => (string) Str::uuid(),
            'user_id' => User::factory(),
            'from' => $fromYear,
            'to' => $this->faker->numberBetween($fromYear, 2023),
            'role' => $this->faker->jobTitle,
            'company' => $this->faker->company,
            'description' => $this->faker->paragraph,
        ];
    }
}
