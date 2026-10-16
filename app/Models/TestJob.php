<?php

namespace App\Models;

use App\Enums\TestJobStatus;
use Database\Factories\TestJobFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['sample_id', 'laboratory_unit_id', 'assigned_to', 'job_number', 'status', 'started_at', 'completed_at'])]
class TestJob extends Model
{
    /** @use HasFactory<TestJobFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => TestJobStatus::class,
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function sample(): BelongsTo
    {
        return $this->belongsTo(Sample::class);
    }

    public function laboratoryUnit(): BelongsTo
    {
        return $this->belongsTo(LaboratoryUnit::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function testResults(): HasMany
    {
        return $this->hasMany(TestResult::class);
    }
}
