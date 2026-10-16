<?php

namespace Database\Factories;

use App\Enums\ReportStatus;
use App\Models\Employee;
use App\Models\ReportTemplate;
use App\Models\TestReport;
use App\Models\TestRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TestReport>
 */
class TestReportFactory extends Factory
{
    protected $model = TestReport::class;

    public function definition(): array
    {
        return [
            'test_request_id' => TestRequest::factory(),
            'report_template_id' => ReportTemplate::factory(),
            'parent_report_id' => null,
            'verifier_id' => Employee::factory(),
            'validator_id' => Employee::factory(),
            'issued_by' => Employee::factory(),
            'report_number' => 'LHU-'.date('Y').'-'.fake()->unique()->numerify('#####'),
            'version' => 1,
            'status' => ReportStatus::Draft,
            'considerations' => 'Hasil uji memenuhi baku mutu air minum sesuai Permenkes.',
            'file_path' => null,
            'validated_on' => null,
            'issued_at' => null,
        ];
    }

    public function final(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ReportStatus::Final,
            'file_path' => 'reports/lhu-'.fake()->uuid().'.pdf',
            'validated_on' => now()->format('Y-m-d'),
            'issued_at' => now(),
        ]);
    }
}
