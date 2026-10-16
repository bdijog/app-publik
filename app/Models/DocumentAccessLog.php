<?php

namespace App\Models;

use App\Enums\DocumentAccessAction;
use Database\Factories\DocumentAccessLogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['test_report_id', 'customer_id', 'employee_id', 'action', 'ip_address', 'accessed_at'])]
class DocumentAccessLog extends Model
{
    /** @use HasFactory<DocumentAccessLogFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'action' => DocumentAccessAction::class,
            'accessed_at' => 'datetime',
        ];
    }

    public function testReport(): BelongsTo
    {
        return $this->belongsTo(TestReport::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
