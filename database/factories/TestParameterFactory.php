<?php

namespace Database\Factories;

use App\Enums\ResultType;
use App\Models\ServiceGroup;
use App\Models\TestParameter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TestParameter>
 */
class TestParameterFactory extends Factory
{
    protected $model = TestParameter::class;

    public function definition(): array
    {
        return [
            'service_group_id' => ServiceGroup::factory(),
            'name' => fake()->word(),
            'unit' => fake()->randomElement(['mg/L', 'CFU/100ml', 'NTU', 'pH', 'µg/m3']),
            'method' => 'SNI '.fake()->numerify('####:2020'),
            'result_type' => ResultType::Numeric,
            'threshold_min' => 0.0,
            'threshold_max' => fake()->randomFloat(2, 5, 100),
            'threshold_text' => null,
            'is_active' => true,
        ];
    }

    public function textResult(): static
    {
        return $this->state(fn (array $attributes) => [
            'result_type' => ResultType::Text,
            'unit' => null,
            'threshold_min' => null,
            'threshold_max' => null,
            'threshold_text' => 'Negatif / Tidak Terdeteksi',
        ]);
    }
}
