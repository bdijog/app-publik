<?php

namespace App\Models;

use Database\Factories\ResultRevisionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['test_result_id', 'revised_by', 'old_value', 'new_value', 'reason', 'created_at'])]
class ResultRevision extends Model
{
    /** @use HasFactory<ResultRevisionFactory> */
    use HasFactory;

    public $timestamps = false;

    protected static function booted(): void
    {
        static::creating(function (ResultRevision $revision) {
            $revision->created_at = $revision->created_at ?? now();
        });
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function testResult(): BelongsTo
    {
        return $this->belongsTo(TestResult::class);
    }

    public function revisedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'revised_by');
    }
}
