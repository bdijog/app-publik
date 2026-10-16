<?php

namespace Database\Factories;

use App\Models\LaboratoryUnit;
use App\Models\ServiceGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceGroup>
 */
class ServiceGroupFactory extends Factory
{
    protected $model = ServiceGroup::class;

    public function definition(): array
    {
        return [
            'laboratory_unit_id' => LaboratoryUnit::factory(),
            'code' => strtoupper(fake()->unique()->bothify('GRP-###')),
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
