<?php

namespace Database\Factories;

use App\Enums\TestResultStatus;
use App\Models\Employee;
use App\Models\TestJob;
use App\Models\TestRequestParameter;
use App\Models\TestResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TestResult>
 */
class TestResultFactory extends Factory
{
    protected $model = TestResult::class;

    public function definition(): array
    {
        return [
            'test_job_id' => TestJob::factory(),
            'test_request_parameter_id' => TestRequestParameter::factory(),
            'analyst_id' => Employee::factory(),
            'value_numeric' => fake()->randomFloat(2, 6.5, 8.5),
            'value_text' => null,
            'unit' => 'pH',
            'method' => 'SNI 06-6989.11-2004',
            'tested_at' => now()->format('Y-m-d'),
            'notes' => fake()->optional()->sentence(),
            'status' => TestResultStatus::Draft,
            'is_locked' => false,
        ];
    }

    public function numeric(): static
    {
        return $this->state(fn (array $attributes) => [
            'value_numeric' => fake()->randomFloat(2, 1, 50),
            'value_text' => null,
        ]);
    }

    public function text(): static
    {
        return $this->state(fn (array $attributes) => [
            'value_numeric' => null,
            'value_text' => 'Negatif (0 CFU/100ml)',
            'unit' => 'CFU/100ml',
        ]);
    }

    public function validated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TestResultStatus::Validated,
            'is_locked' => true,
        ]);
    }
}
