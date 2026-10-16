<?php

namespace Database\Factories;

use App\Models\ServiceGroup;
use App\Models\Tariff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tariff>
 */
class TariffFactory extends Factory
{
    protected $model = Tariff::class;

    public function definition(): array
    {
        return [
            'service_group_id' => ServiceGroup::factory(),
            'service_package_id' => null,
            'test_parameter_id' => null,
            'amount' => fake()->numberBetween(50, 800) * 1000,
            'effective_from' => '2025-01-01',
            'effective_until' => null,
        ];
    }
}
