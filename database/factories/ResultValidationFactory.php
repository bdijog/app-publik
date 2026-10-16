<?php

namespace Database\Factories;

use App\Enums\ValidationDecision;
use App\Enums\ValidationStage;
use App\Models\Employee;
use App\Models\ResultValidation;
use App\Models\TestResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResultValidation>
 */
class ResultValidationFactory extends Factory
{
    protected $model = ResultValidation::class;

    public function definition(): array
    {
        return [
            'test_result_id' => TestResult::factory(),
            'validator_id' => Employee::factory(),
            'stage' => ValidationStage::ManagerLab,
            'decision' => ValidationDecision::Approved,
            'notes' => 'Hasil uji sesuai dengan baku mutu dan metode standar.',
            'decided_at' => now(),
        ];
    }
}
