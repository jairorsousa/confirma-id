<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Verification;
use App\Models\VerificationReview;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VerificationReview>
 */
class VerificationReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'verification_id' => Verification::factory(),
            'admin_id' => User::factory(),
            'decision' => fake()->randomElement([
                VerificationReview::DECISION_APPROVED,
                VerificationReview::DECISION_REJECTED,
                VerificationReview::DECISION_CORRECTION_REQUESTED,
                VerificationReview::DECISION_BLOCKED,
            ]),
            'reason' => fake()->optional()->sentence(6),
            'notes' => fake()->optional()->paragraph(),
            'decided_at' => now(),
        ];
    }
}
