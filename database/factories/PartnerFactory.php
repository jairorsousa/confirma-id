<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Partner>
 */
class PartnerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'legal_name' => fake()->company(),
            'trade_name' => fake()->optional()->companySuffix(),
            'cnpj' => fake()->unique()->numerify('##############'),
            'responsible_name' => fake()->name(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->numerify('113#######'),
            'status' => Partner::STATUS_ACTIVE,
            'plan_name' => 'basic',
            'can_query_cpf' => false,
            'api_key_hash' => Hash::make(fake()->uuid()),
        ];
    }
}
