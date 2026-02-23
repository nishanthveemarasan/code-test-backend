<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Education>
 */
class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->numberBetween(2000, 2015);

        return [
            'user_id' => User::factory(),
            'from' => $start,
            'to' => $start + rand(2, 5),
            'course' => $this->faker->randomElement([
                'B.Eng Civil Engineering',
                'BSc Computer Science',
                'MBA Project Management'
            ]),
            'institution' => $this->faker->company . ' University',
            'description' => $this->faker->paragraph,
        ];
    }
}
