<?php

namespace App\Models;

use Database\Factories\TestReportItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'test_report_id',
    'test_result_id',
    'sample_name',
    'sample_number',
    'parameter',
    'unit',
    'result',
    'method',
    'threshold',
])]
class TestReportItem extends Model
{
    /** @use HasFactory<TestReportItemFactory> */
    use HasFactory;

    public function testReport(): BelongsTo
    {
        return $this->belongsTo(TestReport::class);
    }

    public function testResult(): BelongsTo
    {
        return $this->belongsTo(TestResult::class);
    }
}
