<?php

namespace App\Models;

use App\Enums\SampleDecision;
use Database\Factories\SampleReceiptFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['sample_id', 'received_by', 'received_at', 'received_volume', 'condition', 'decision', 'rejection_reason', 'follow_up'])]
class SampleReceipt extends Model
{
    /** @use HasFactory<SampleReceiptFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'received_at' => 'datetime',
            'received_volume' => 'decimal:2',
            'decision' => SampleDecision::class,
        ];
    }

    public function sample(): BelongsTo
    {
        return $this->belongsTo(Sample::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'received_by');
    }
}
