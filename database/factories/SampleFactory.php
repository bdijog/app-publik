<?php

namespace Database\Factories;

use App\Enums\SampleStatus;
use App\Models\Sample;
use App\Models\SampleType;
use App\Models\TestRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sample>
 */
class SampleFactory extends Factory
{
    protected $model = Sample::class;

    public function definition(): array
    {
        return [
            'test_request_id' => TestRequest::factory(),
            'sample_type_id' => SampleType::factory(),
            'sample_code' => 'SMP-'.date('Ymd').'-'.fake()->unique()->numerify('####'),
            'sample_name' => fake()->words(2, true),
            'quantity' => fake()->randomFloat(2, 100, 1500),
            'unit' => 'ml',
            'collected_at' => now()->subDay(),
            'status' => SampleStatus::Pending,
        ];
    }
}
