<?php

namespace Database\Factories;

use App\Models\LaboratoryUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LaboratoryUnit>
 */
class LaboratoryUnitFactory extends Factory
{
    protected $model = LaboratoryUnit::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->lexify('LAB-???')),
            'name' => 'Instalasi '.fake()->words(2, true),
        ];
    }
}
