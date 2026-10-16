<?php

namespace App\Models;

use App\Enums\TestRequestStatus;
use Database\Factories\StatusHistoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['test_request_id', 'changed_by', 'status', 'note', 'created_at'])]
class StatusHistory extends Model
{
    /** @use HasFactory<StatusHistoryFactory> */
    use HasFactory;

    public $timestamps = false;

    protected static function booted(): void
    {
        static::creating(function (StatusHistory $history) {
            $history->created_at = $history->created_at ?? now();
        });
    }

    protected function casts(): array
    {
        return [
            'status' => TestRequestStatus::class,
            'created_at' => 'datetime',
        ];
    }

    public function testRequest(): BelongsTo
    {
        return $this->belongsTo(TestRequest::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'changed_by');
    }
}
