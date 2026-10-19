<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\RegistrationChannel;
use App\Enums\TestRequestStatus;
use Database\Factories\TestRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'request_number',
    'fpps_number',
    'customer_id',
    'service_group_id',
    'service_package_id',
    'created_by',
    'purpose',
    'channel',
    'sampling_location',
    'sampling_date',
    'notes',
    'total_amount',
    'payment_status',
    'status',
    'estimated_completion_date',
    'registered_at',
])]
class TestRequest extends Model
{
    /** @use HasFactory<TestRequestFactory> */
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::creating(function (TestRequest $testRequest) {
            if (empty($testRequest->registered_at)) {
                $testRequest->registered_at = now();
            }

            if (empty($testRequest->request_number)) {
                $yearMonth = now()->format('Ym');
                $latest = static::withTrashed()->where('request_number', 'like', "REQ-{$yearMonth}-%")->latest('id')->first();
                $seq = $latest ? ((int) substr((string) $latest->request_number, -4)) + 1 : 1;
                $testRequest->request_number = sprintf('REQ-%s-%04d', $yearMonth, $seq);
            }

            if (empty($testRequest->fpps_number)) {
                $yearMonth = now()->format('Ym');
                $latest = static::withTrashed()->where('fpps_number', 'like', "FPPS-{$yearMonth}-%")->latest('id')->first();
                $seq = $latest ? ((int) substr((string) $latest->fpps_number, -4)) + 1 : 1;
                $testRequest->fpps_number = sprintf('FPPS-%s-%04d', $yearMonth, $seq);
            }

            if (empty($testRequest->created_by) && auth('employee')->check()) {
                $testRequest->created_by = auth('employee')->id();
            }

            if (empty($testRequest->status)) {
                $testRequest->status = TestRequestStatus::Submitted;
            }

            if (empty($testRequest->payment_status)) {
                $testRequest->payment_status = PaymentStatus::Unpaid;
            }
        });

        static::created(function (TestRequest $testRequest) {
            StatusHistory::create([
                'test_request_id' => $testRequest->id,
                'changed_by' => auth('employee')->id() ?? $testRequest->created_by,
                'status' => $testRequest->status instanceof TestRequestStatus ? $testRequest->status->value : (string) $testRequest->status,
                'note' => 'Permohonan pengujian berhasil dibuat melalui kanal '.($testRequest->channel instanceof RegistrationChannel ? $testRequest->channel->label() : (string) $testRequest->channel),
                'created_at' => now(),
            ]);
        });
    }

    protected function casts(): array
    {
        return [
            'channel' => RegistrationChannel::class,
            'payment_status' => PaymentStatus::class,
            'status' => TestRequestStatus::class,
            'total_amount' => 'decimal:2',
            'sampling_date' => 'date',
            'estimated_completion_date' => 'date',
            'registered_at' => 'datetime',
        ];
    }

    public function scopeStatus(Builder $query, TestRequestStatus|string $status): Builder
    {
        $value = $status instanceof TestRequestStatus ? $status->value : $status;

        return $query->where('status', $value);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function serviceGroup(): BelongsTo
    {
        return $this->belongsTo(ServiceGroup::class);
    }

    public function servicePackage(): BelongsTo
    {
        return $this->belongsTo(ServicePackage::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(TestRequestDocument::class);
    }

    public function parameters(): HasMany
    {
        return $this->hasMany(TestRequestParameter::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(StatusHistory::class)->orderBy('created_at', 'asc');
    }

    public function samples(): HasMany
    {
        return $this->hasMany(Sample::class);
    }

    public function testReports(): HasMany
    {
        return $this->hasMany(TestReport::class);
    }
}
