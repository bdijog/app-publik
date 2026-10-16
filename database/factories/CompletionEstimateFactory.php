<?php

namespace Database\Factories;

use App\Enums\EstimateStartPoint;
use App\Models\CompletionEstimate;
use App\Models\ServiceGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompletionEstimate>
 */
class CompletionEstimateFactory extends Factory
{
    protected $model = CompletionEstimate::class;

    public function definition(): array
    {
        return [
            'service_group_id' => ServiceGroup::factory(),
            'days' => fake()->randomElement([8, 10, 14]),
            'start_point' => fake()->randomElement(EstimateStartPoint::cases()),
            'effective_from' => '2025-01-01',
        ];
    }
}
