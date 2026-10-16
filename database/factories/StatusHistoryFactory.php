<?php

namespace Database\Factories;

use App\Enums\TestRequestStatus;
use App\Models\Employee;
use App\Models\StatusHistory;
use App\Models\TestRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StatusHistory>
 */
class StatusHistoryFactory extends Factory
{
    protected $model = StatusHistory::class;

    public function definition(): array
    {
        return [
            'test_request_id' => TestRequest::factory(),
            'changed_by' => Employee::factory(),
            'status' => TestRequestStatus::Submitted,
            'note' => fake()->sentence(),
            'created_at' => now(),
        ];
    }
}
