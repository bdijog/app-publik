<?php

namespace Database\Factories;

use App\Models\SampleType;
use App\Models\ServiceGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SampleType>
 */
class SampleTypeFactory extends Factory
{
    protected $model = SampleType::class;

    public function definition(): array
    {
        return [
            'service_group_id' => ServiceGroup::factory(),
            'name' => 'Sampel '.fake()->words(2, true),
            'min_volume' => fake()->randomFloat(2, 50, 1000),
            'container_requirement' => fake()->randomElement(['Botol Kaca Steril', 'Jerigen Plastik 2L', 'Coolbox suhu 4°C', 'Plastik Zip Steril']),
            'requirements' => fake()->sentence(),
        ];
    }
}
