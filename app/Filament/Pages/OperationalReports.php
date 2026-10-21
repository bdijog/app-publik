<?php

namespace App\Filament\Pages;

use App\Enums\PermissionEnum;
use App\Enums\SampleDecision;
use App\Models\Payment;
use App\Models\Sample;
use App\Models\SampleReceipt;
use App\Models\ServiceGroup;
use App\Models\TestReport;
use App\Models\TestRequest;
use App\Services\Export\Excel\OperationalReportExcelExporter;
use App\Services\Export\Pdf\OperationalReportPdfGenerator;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use UnitEnum;

class OperationalReports extends Page
{
    protected string $view = 'filament.pages.operational-reports';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Rekapitulasi Operasional';

    protected static ?string $title = 'Rekapitulasi Operasional Layanan';

    public static function getNavigationLabel(): string
    {
        return __('Operational Reports');
    }

    public function getTitle(): string|Htmlable
    {
        return __('Operational Reports');
    }

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth('employee')->user()?->can(PermissionEnum::ReportExport->value) ?? false;
    }

    public ?string $startDate = null;

    public ?string $endDate = null;

    public string $activePreset = 'this_month';

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->activePreset = 'this_month';
    }

    public function setPreset(string $preset): void
    {
        $this->activePreset = $preset;

        match ($preset) {
            'today' => [
                $this->startDate = now()->format('Y-m-d'),
                $this->endDate = now()->format('Y-m-d'),
            ],
            '7_days' => [
                $this->startDate = now()->subDays(6)->format('Y-m-d'),
                $this->endDate = now()->format('Y-m-d'),
            ],
            '30_days' => [
                $this->startDate = now()->subDays(29)->format('Y-m-d'),
                $this->endDate = now()->format('Y-m-d'),
            ],
            'this_month' => [
                $this->startDate = now()->startOfMonth()->format('Y-m-d'),
                $this->endDate = now()->endOfMonth()->format('Y-m-d'),
            ],
            'last_month' => [
                $this->startDate = now()->subMonth()->startOfMonth()->format('Y-m-d'),
                $this->endDate = now()->subMonth()->endOfMonth()->format('Y-m-d'),
            ],
            'this_year' => [
                $this->startDate = now()->startOfYear()->format('Y-m-d'),
                $this->endDate = now()->endOfYear()->format('Y-m-d'),
            ],
            default => null,
        };
    }

    public function updatedStartDate(): void
    {
        $this->activePreset = 'custom';
    }

    public function updatedEndDate(): void
    {
        $this->activePreset = 'custom';
    }

    public function filter(): void
    {
        // Livewire updates automatically
    }

    /**
     * Ekspor seluruh data operasional ke berkas Excel (.xlsx).
     */
    public function exportExcel(): mixed
    {
        return OperationalReportExcelExporter::export(
            $this->startDate,
            $this->endDate
        );
    }

    /**
     * Ekspor ringkasan eksekutif ke berkas PDF resmi (.pdf).
     */
    public function exportPdf(): mixed
    {
        return OperationalReportPdfGenerator::download(
            $this->startDate,
            $this->endDate
        );
    }

    /**
     * @return array{Carbon, Carbon}
     */
    public function getParsedDateRange(): array
    {
        try {
            $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : now()->startOfMonth()->startOfDay();
        } catch (\Throwable) {
            $start = now()->startOfMonth()->startOfDay();
        }

        try {
            $end = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : now()->endOfMonth()->endOfDay();
        } catch (\Throwable) {
            $end = now()->endOfMonth()->endOfDay();
        }

        return [$start, $end];
    }

    public function getSummaryStats(): array
    {
        [$start, $end] = $this->getParsedDateRange();

        $totalRequests = TestRequest::whereBetween('created_at', [$start, $end])->count();
        $totalRevenue = (float) Payment::whereBetween('created_at', [$start, $end])->sum('amount');
        $totalSamples = Sample::whereBetween('created_at', [$start, $end])->count();
        $totalReportsIssued = TestReport::where('status', 'final')
            ->whereBetween('issued_at', [$start, $end])
            ->count();

        $totalCustomers = TestRequest::whereBetween('created_at', [$start, $end])
            ->distinct('customer_id')
            ->count('customer_id');

        $paidRequests = TestRequest::whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->count();

        return [
            'total_requests' => $totalRequests,
            'total_revenue' => $totalRevenue,
            'total_samples' => $totalSamples,
            'total_reports_issued' => $totalReportsIssued,
            'total_customers' => $totalCustomers,
            'paid_requests' => $paidRequests,
            'paid_rate' => $totalRequests > 0 ? round(($paidRequests / $totalRequests) * 100, 1) : 100,
        ];
    }

    public function getPipelineStats(): array
    {
        [$start, $end] = $this->getParsedDateRange();

        $base = TestRequest::whereBetween('created_at', [$start, $end]);

        $stage1 = (clone $base)->whereIn('status', ['draft', 'submitted'])->count();
        $stage2 = (clone $base)->whereIn('status', ['awaiting_payment', 'awaiting_sample'])->count();
        $stage3 = (clone $base)->whereIn('status', ['in_progress', 'validated'])->count();
        $stage4 = (clone $base)->whereIn('status', ['report_issued', 'completed'])->count();
        $cancelled = (clone $base)->where('status', 'cancelled')->count();

        $total = $stage1 + $stage2 + $stage3 + $stage4 + $cancelled;

        return [
            'total' => $total,
            'stages' => [
                [
                    'name' => '1. Registrasi & Verifikasi',
                    'count' => $stage1,
                    'desc' => 'Draf & Menunggu Verifikasi',
                    'color' => 'amber',
                ],
                [
                    'name' => '2. Pembayaran & Sampel',
                    'count' => $stage2,
                    'desc' => 'Menunggu Bayar & Sampel Masuk',
                    'color' => 'blue',
                ],
                [
                    'name' => '3. Pengujian Lab',
                    'count' => $stage3,
                    'desc' => 'Pengerjaan Analis & Validasi',
                    'color' => 'indigo',
                ],
                [
                    'name' => '4. Terbit LHU & Selesai',
                    'count' => $stage4,
                    'desc' => 'Laporan Sah / Sudah Diterima',
                    'color' => 'emerald',
                ],
            ],
            'cancelled' => $cancelled,
        ];
    }

    public function getChannelStats(): array
    {
        [$start, $end] = $this->getParsedDateRange();

        $channels = [
            'online' => ['label' => 'Web Portal Publik', 'count' => 0, 'badge' => 'Self-service Online', 'color' => 'teal'],
            'counter' => ['label' => 'Loket Pelayanan Langsung', 'count' => 0, 'badge' => 'Front Office / CS', 'color' => 'blue'],
            'whatsapp' => ['label' => 'WhatsApp Layanan', 'count' => 0, 'badge' => 'Konsultasi WA', 'color' => 'emerald'],
        ];

        $counts = TestRequest::whereBetween('created_at', [$start, $end])
            ->selectRaw('channel, count(*) as total')
            ->groupBy('channel')
            ->pluck('total', 'channel')
            ->toArray();

        $total = array_sum($counts);

        foreach ($channels as $key => &$data) {
            $data['count'] = $counts[$key] ?? 0;
            $data['percent'] = $total > 0 ? round(($data['count'] / $total) * 100, 1) : 0;
        }

        return [
            'total' => $total,
            'channels' => $channels,
        ];
    }

    public function getServiceGroupStats(): Collection
    {
        [$start, $end] = $this->getParsedDateRange();

        $groups = ServiceGroup::withCount([
            'testRequests' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            },
        ])
            ->with(['servicePackages', 'laboratoryUnit'])
            ->get();

        $totalRequests = $groups->sum('test_requests_count');

        return $groups->map(function ($group) use ($totalRequests, $start, $end) {
            $revenue = (float) TestRequest::where('service_group_id', $group->id)
                ->whereBetween('created_at', [$start, $end])
                ->sum('total_amount');

            $group->revenue = $revenue;
            $group->share_percent = $totalRequests > 0 ? round(($group->test_requests_count / $totalRequests) * 100, 1) : 0;

            return $group;
        })->sortByDesc('test_requests_count');
    }

    public function getSlaComplianceStats(): array
    {
        [$start, $end] = $this->getParsedDateRange();

        $requests = TestRequest::whereBetween('created_at', [$start, $end])
            ->whereNotNull('estimated_completion_date')
            ->get();

        $totalWithSla = $requests->count();
        $completedOnTime = 0;
        $overdue = 0;
        $inProgress = 0;

        foreach ($requests as $req) {
            $estimatedDate = Carbon::parse($req->estimated_completion_date)->endOfDay();

            if ($req->status === 'completed' || $req->status === 'report_issued') {
                if ($req->updated_at->lte($estimatedDate)) {
                    $completedOnTime++;
                } else {
                    $overdue++;
                }
            } elseif ($req->status === 'cancelled') {
                continue;
            } else {
                if (now()->gt($estimatedDate)) {
                    $overdue++;
                } else {
                    $inProgress++;
                }
            }
        }

        $complianceRate = ($completedOnTime + $overdue) > 0
            ? round(($completedOnTime / ($completedOnTime + $overdue)) * 100, 1)
            : 100;

        return [
            'total_with_sla' => $totalWithSla,
            'completed_on_time' => $completedOnTime,
            'overdue' => $overdue,
            'in_progress' => $inProgress,
            'compliance_rate' => $complianceRate,
        ];
    }

    public function getSampleReceiptStats(): array
    {
        [$start, $end] = $this->getParsedDateRange();

        $receipts = SampleReceipt::whereBetween('received_at', [$start, $end])->get();

        $accepted = $receipts->where('decision', SampleDecision::Accepted)->count();
        $rejected = $receipts->where('decision', SampleDecision::Rejected)->count();
        $total = $accepted + $rejected;

        $rejections = SampleReceipt::with(['sample.sampleType', 'receivedBy'])
            ->where('decision', SampleDecision::Rejected)
            ->whereBetween('received_at', [$start, $end])
            ->latest('received_at')
            ->take(8)
            ->get();

        return [
            'total' => $total,
            'accepted' => $accepted,
            'rejected' => $rejected,
            'rejection_rate' => $total > 0 ? round(($rejected / $total) * 100, 1) : 0,
            'rejections_list' => $rejections,
        ];
    }
}
