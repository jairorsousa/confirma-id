<?php

namespace Database\Factories;

use App\Models\Partner;
use App\Models\PartnerQuery;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PartnerQuery>
 */
class PartnerQueryFactory extends Factory
{
    public function definition(): array
    {
        $term = fake()->safeEmail();

        return [
            'partner_id' => Partner::factory(),
            'user_id' => User::factory(),
            'query_type' => PartnerQuery::TYPE_EMAIL,
            'queried_term_hash' => hash('sha256', mb_strtolower($term)),
            'queried_term_masked' => substr($term, 0, 2).'***@***',
            'result' => fake()->randomElement([
                PartnerQuery::RESULT_APPROVED,
                PartnerQuery::RESULT_UNDER_REVIEW,
                PartnerQuery::RESULT_NOT_FOUND,
                PartnerQuery::RESULT_BLOCKED,
            ]),
            'ip_address' => fake()->ipv4(),
            'origin' => 'web',
            'credential_label' => 'default',
        ];
    }
}
