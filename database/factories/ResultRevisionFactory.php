<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\ResultRevision;
use App\Models\TestResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResultRevision>
 */
class ResultRevisionFactory extends Factory
{
    protected $model = ResultRevision::class;

    public function definition(): array
    {
        return [
            'test_result_id' => TestResult::factory(),
            'revised_by' => Employee::factory(),
            'old_value' => '7.12',
            'new_value' => '7.18',
            'reason' => 'Koreksi faktor kalibrasi elektroda pH meter.',
            'created_at' => now(),
        ];
    }
}
