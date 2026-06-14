<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Verification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Verification>
 */
class VerificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'attempt_number' => 1,
            'document_type' => fake()->randomElement(['rg', 'cnh']),
            'status' => Verification::STATUS_PENDING,
            'verification_code' => null,
            'submitted_at' => null,
            'approved_at' => null,
            'expires_at' => null,
        ];
    }

    public function underReview(): static
    {
        return $this->state(fn (): array => [
            'status' => Verification::STATUS_UNDER_REVIEW,
            'submitted_at' => now(),
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (): array => [
            'status' => Verification::STATUS_APPROVED,
            'verification_code' => 'CID-'.fake()->unique()->numerify('######'),
            'submitted_at' => now()->subDay(),
            'approved_at' => now(),
            'expires_at' => now()->addYear(),
        ]);
    }
}
