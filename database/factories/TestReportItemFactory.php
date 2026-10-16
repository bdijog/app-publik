<?php

namespace Database\Factories;

use App\Models\TestReport;
use App\Models\TestReportItem;
use App\Models\TestResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TestReportItem>
 */
class TestReportItemFactory extends Factory
{
    protected $model = TestReportItem::class;

    public function definition(): array
    {
        return [
            'test_report_id' => TestReport::factory(),
            'test_result_id' => TestResult::factory(),
            'sample_name' => 'Air Bersih Kran Utama',
            'sample_number' => 'SMP-001',
            'parameter' => 'pH',
            'unit' => 'pH Unit',
            'result' => '7.2',
            'method' => 'SNI 06-6989.11-2004',
            'threshold' => '6.5 - 8.5',
        ];
    }
}
