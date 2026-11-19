<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;
use Laravel\Passport\Client;

abstract class TestCase extends BaseTestCase
{
    protected function authenticateUser(?User $user = null): User
    {
        $loggedInUser = $user ?? User::factory()->create();
        Passport::actingAs($loggedInUser);

        return $loggedInUser;
    }

    protected function authenticateClient(): void
    {
        Passport::actingAsClient(
            Client::factory()->create(),
            ['client_authentication']
        );
    }
}
