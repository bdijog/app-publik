<?php

namespace App\Models;

use Database\Factories\ReportHandoverFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['test_report_id', 'handed_by', 'received_by_name', 'handed_at', 'notes'])]
class ReportHandover extends Model
{
    /** @use HasFactory<ReportHandoverFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'handed_at' => 'datetime',
        ];
    }

    public function testReport(): BelongsTo
    {
        return $this->belongsTo(TestReport::class);
    }

    public function handedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'handed_by');
    }
}
