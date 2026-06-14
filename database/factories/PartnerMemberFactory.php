<?php

namespace Database\Factories;

use App\Models\Partner;
use App\Models\PartnerMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PartnerMember>
 */
class PartnerMemberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'partner_id' => Partner::factory(),
            'user_id' => User::factory(),
            'role' => PartnerMember::ROLE_MEMBER,
            'status' => Partner::STATUS_ACTIVE,
        ];
    }

    public function owner(): static
    {
        return $this->state(fn (): array => [
            'role' => PartnerMember::ROLE_OWNER,
        ]);
    }
}
