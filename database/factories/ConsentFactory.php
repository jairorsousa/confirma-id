<?php

namespace Database\Factories;

use App\Models\Consent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Consent>
 */
class ConsentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['terms_of_use', 'privacy_policy', 'identity_verification']),
            'version' => '1.0',
            'accepted_at' => now(),
            'ip_address' => fake()->ipv4(),
        ];
    }
}
