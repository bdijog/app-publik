<?php

namespace App\Models;

use App\Enums\SampleStatus;
use Database\Factories\SampleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['test_request_id', 'sample_type_id', 'sample_code', 'sample_name', 'quantity', 'unit', 'collected_at', 'status'])]
class Sample extends Model
{
    /** @use HasFactory<SampleFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Sample $sample) {
            if (empty($sample->sample_code)) {
                $yearMonth = now()->format('Ym');
                $latest = static::where('sample_code', 'like', "SMP-{$yearMonth}-%")->latest('id')->first();
                $seq = $latest ? ((int) substr((string) $latest->sample_code, -4)) + 1 : 1;
                $sample->sample_code = sprintf('SMP-%s-%04d', $yearMonth, $seq);
            }

            if (empty($sample->status)) {
                $sample->status = SampleStatus::Pending;
            }

            if (empty($sample->quantity)) {
                $sample->quantity = 1;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'status' => SampleStatus::class,
            'collected_at' => 'datetime',
        ];
    }

    public function testRequest(): BelongsTo
    {
        return $this->belongsTo(TestRequest::class);
    }

    public function sampleType(): BelongsTo
    {
        return $this->belongsTo(SampleType::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(SampleReceipt::class);
    }

    public function latestReceipt(): HasOne
    {
        return $this->hasOne(SampleReceipt::class)->latestOfMany();
    }

    public function testJobs(): HasMany
    {
        return $this->hasMany(TestJob::class);
    }
}
