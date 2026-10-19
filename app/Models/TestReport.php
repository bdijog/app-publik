<?php

namespace App\Models;

use App\Enums\ReportStatus;
use Database\Factories\TestReportFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'test_request_id',
    'report_template_id',
    'parent_report_id',
    'verifier_id',
    'validator_id',
    'issued_by',
    'report_number',
    'version',
    'status',
    'considerations',
    'file_path',
    'validated_on',
    'issued_at',
])]
class TestReport extends Model
{
    /** @use HasFactory<TestReportFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (TestReport $report) {
            if (empty($report->report_number)) {
                $year = date('Y');
                $count = static::whereYear('created_at', $year)->count() + 1;
                $report->report_number = sprintf('LHU-%s-%05d', $year, $count);
            }

            if (empty($report->version)) {
                $report->version = 1;
            }

            if (empty($report->status)) {
                $report->status = ReportStatus::Draft;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'status' => ReportStatus::class,
            'validated_on' => 'date',
            'issued_at' => 'datetime',
        ];
    }

    public function testRequest(): BelongsTo
    {
        return $this->belongsTo(TestRequest::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ReportTemplate::class, 'report_template_id');
    }

    public function parentReport(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_report_id');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(self::class, 'parent_report_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'verifier_id');
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'validator_id');
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'issued_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TestReportItem::class);
    }

    public function handovers(): HasMany
    {
        return $this->hasMany(ReportHandover::class);
    }

    public function accessLogs(): HasMany
    {
        return $this->hasMany(DocumentAccessLog::class);
    }
}
