<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\TestRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        return [
            'causer_type' => Employee::class,
            'causer_id' => Employee::factory(),
            'action' => 'test_request.created',
            'subject_type' => TestRequest::class,
            'subject_id' => TestRequest::factory(),
            'properties' => ['channel' => 'counter', 'request_number' => 'REQ-001'],
            'created_at' => now(),
        ];
    }
}
