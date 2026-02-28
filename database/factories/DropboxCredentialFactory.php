<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DropboxCredential>
 */
class DropboxCredentialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Za-z0-9]{10}'),
            'client_id' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'client_secret' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'app_redirect_url' => $this->faker->url(),
            'refresh_token' => null,
            'access_token' => null,
        ];
    }
}
