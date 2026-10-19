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

    protected static function booted(): void
    {
        static::creating(function (TestJob $job) {
            if (empty($job->job_number)) {
                $yearMonth = now()->format('Ym');
                $latest = static::where('job_number', 'like', "JOB-{$yearMonth}-%")->latest('id')->first();
                $seq = $latest ? ((int) substr((string) $latest->job_number, -4)) + 1 : 1;
                $job->job_number = sprintf('JOB-%s-%04d', $yearMonth, $seq);
            }

            if (empty($job->status)) {
                $job->status = TestJobStatus::Pending;
            }
        });
    }

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
