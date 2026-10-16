<?php

namespace App\Models;

use App\Enums\ValidationDecision;
use App\Enums\ValidationStage;
use Database\Factories\ResultValidationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['test_result_id', 'validator_id', 'stage', 'decision', 'notes', 'decided_at'])]
class ResultValidation extends Model
{
    /** @use HasFactory<ResultValidationFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'stage' => ValidationStage::class,
            'decision' => ValidationDecision::class,
            'decided_at' => 'datetime',
        ];
    }

    public function testResult(): BelongsTo
    {
        return $this->belongsTo(TestResult::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'validator_id');
    }
}
