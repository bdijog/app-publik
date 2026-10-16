<?php

namespace App\Models;

use App\Enums\TestResultStatus;
use Database\Factories\TestResultFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'test_job_id',
    'test_request_parameter_id',
    'analyst_id',
    'value_numeric',
    'value_text',
    'unit',
    'method',
    'tested_at',
    'notes',
    'status',
    'is_locked',
])]
class TestResult extends Model
{
    /** @use HasFactory<TestResultFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'value_numeric' => 'decimal:6',
            'tested_at' => 'date',
            'status' => TestResultStatus::class,
            'is_locked' => 'boolean',
        ];
    }

    public function testJob(): BelongsTo
    {
        return $this->belongsTo(TestJob::class);
    }

    public function testRequestParameter(): BelongsTo
    {
        return $this->belongsTo(TestRequestParameter::class);
    }

    public function analyst(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'analyst_id');
    }

    public function validations(): HasMany
    {
        return $this->hasMany(ResultValidation::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(ResultRevision::class);
    }

    public function reportItems(): HasMany
    {
        return $this->hasMany(TestReportItem::class);
    }
}
