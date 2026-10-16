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
