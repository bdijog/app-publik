<?php

namespace Database\Factories;

use App\Models\TestParameter;
use App\Models\TestRequest;
use App\Models\TestRequestParameter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TestRequestParameter>
 */
class TestRequestParameterFactory extends Factory
{
    protected $model = TestRequestParameter::class;

    public function definition(): array
    {
        return [
            'test_request_id' => TestRequest::factory(),
            'test_parameter_id' => TestParameter::factory(),
            'price' => fake()->numberBetween(30, 200) * 1000,
        ];
    }
}
