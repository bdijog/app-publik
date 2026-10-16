<?php

namespace Database\Factories;

use App\Enums\DocumentAccessAction;
use App\Models\Customer;
use App\Models\DocumentAccessLog;
use App\Models\TestReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentAccessLog>
 */
class DocumentAccessLogFactory extends Factory
{
    protected $model = DocumentAccessLog::class;

    public function definition(): array
    {
        return [
            'test_report_id' => TestReport::factory(),
            'customer_id' => Customer::factory(),
            'employee_id' => null,
            'action' => DocumentAccessAction::Download,
            'ip_address' => fake()->ipv4(),
            'accessed_at' => now(),
        ];
    }
}
