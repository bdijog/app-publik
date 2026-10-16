<?php

namespace Database\Factories;

use App\Enums\TestJobStatus;
use App\Models\Employee;
use App\Models\LaboratoryUnit;
use App\Models\Sample;
use App\Models\TestJob;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TestJob>
 */
class TestJobFactory extends Factory
{
    protected $model = TestJob::class;

    public function definition(): array
    {
        return [
            'sample_id' => Sample::factory(),
            'laboratory_unit_id' => LaboratoryUnit::factory(),
            'assigned_to' => Employee::factory(),
            'job_number' => 'JOB-'.date('Ymd').'-'.fake()->unique()->numerify('####'),
            'status' => TestJobStatus::Pending,
            'started_at' => null,
            'completed_at' => null,
        ];
    }
}
