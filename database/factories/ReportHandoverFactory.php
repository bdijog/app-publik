<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\ReportHandover;
use App\Models\TestReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReportHandover>
 */
class ReportHandoverFactory extends Factory
{
    protected $model = ReportHandover::class;

    public function definition(): array
    {
        return [
            'test_report_id' => TestReport::factory(),
            'handed_by' => Employee::factory(),
            'received_by_name' => fake()->name(),
            'handed_at' => now(),
            'notes' => 'Diserahkan langsung di loket pelayanan Balai Labkesmas Ambon.',
        ];
    }
}
