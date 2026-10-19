<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['test_request_id', 'recorded_by', 'amount', 'status', 'paid_at'])]
class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Payment $payment) {
            if (empty($payment->recorded_by) && auth('employee')->check()) {
                $payment->recorded_by = auth('employee')->id();
            }
            if (empty($payment->paid_at) && ($payment->status === PaymentStatus::Paid || $payment->status === 'paid')) {
                $payment->paid_at = now();
            }
        });

        static::saved(function (Payment $payment) {
            if ($payment->status === PaymentStatus::Paid || $payment->status === 'paid') {
                $testRequest = $payment->testRequest;
                if ($testRequest) {
                    $testRequest->payment_status = PaymentStatus::Paid;

                    $estimate = CompletionEstimate::where(function ($q) use ($testRequest) {
                        $q->where('service_group_id', $testRequest->service_group_id)
                            ->orWhereNull('service_group_id');
                    })
                        ->where('start_point', 'payment')
                        ->latest('effective_from')
                        ->first();

                    if ($estimate && empty($testRequest->estimated_completion_date)) {
                        $testRequest->estimated_completion_date = now()->addDays((int) $estimate->days);
                    }

                    $testRequest->save();
                }
            }
        });
    }

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'status' => PaymentStatus::class,
            'paid_at' => 'datetime',
        ];
    }

    public function testRequest(): BelongsTo
    {
        return $this->belongsTo(TestRequest::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'recorded_by');
    }
}
