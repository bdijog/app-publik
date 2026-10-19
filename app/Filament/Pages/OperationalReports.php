<?php

namespace App\Filament\Pages;

use App\Enums\SampleDecision;
use App\Models\Payment;
use App\Models\Sample;
use App\Models\SampleReceipt;
use App\Models\ServiceGroup;
use App\Models\TestReport;
use App\Models\TestRequest;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use UnitEnum;

class OperationalReports extends Page
{
    protected string $view = 'filament.pages.operational-reports';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Rekapitulasi Operasional';

    protected static ?string $title = 'Rekapitulasi Operasional Layanan';

    protected static ?string $navigationLabel = 'Laporan Operasional';

    protected static ?int $navigationSort = 1;

    public ?string $startDate = null;

    public ?string $endDate = null;

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function filter(): void
    {
        // Livewire updates automatically
    }

    public function getSummaryStats(): array
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        $totalRequests = TestRequest::whereBetween('created_at', [$start, $end])->count();
        $totalRevenue = Payment::whereBetween('created_at', [$start, $end])->sum('amount');
        $totalSamples = Sample::whereBetween('created_at', [$start, $end])->count();
        $totalReportsIssued = TestReport::where('status', 'final')
            ->whereBetween('issued_at', [$start, $end])
            ->count();

        return [
            'total_requests' => $totalRequests,
            'total_revenue' => (float) $totalRevenue,
            'total_samples' => $totalSamples,
            'total_reports_issued' => $totalReportsIssued,
        ];
    }

    public function getServiceGroupStats(): Collection
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        return ServiceGroup::withCount([
            'testRequests' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            },
        ])
            ->with(['servicePackages'])
            ->get();
    }

    public function getSlaComplianceStats(): array
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        $requests = TestRequest::whereBetween('created_at', [$start, $end])
            ->whereNotNull('estimated_completion_date')
            ->get();

        $totalWithSla = $requests->count();
        $completedOnTime = 0;
        $overdue = 0;
        $inProgress = 0;

        foreach ($requests as $req) {
            $estimatedDate = Carbon::parse($req->estimated_completion_date)->endOfDay();

            if ($req->status === 'completed') {
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

        $complianceRate = $totalWithSla > 0
            ? round(($completedOnTime / $totalWithSla) * 100, 1)
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
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        $receipts = SampleReceipt::whereBetween('received_at', [$start, $end])->get();

        $accepted = $receipts->where('decision', SampleDecision::Accepted)->count();
        $rejected = $receipts->where('decision', SampleDecision::Rejected)->count();
        $total = $accepted + $rejected;

        $rejections = SampleReceipt::with(['sample', 'receivedBy'])
            ->where('decision', SampleDecision::Rejected)
            ->whereBetween('received_at', [$start, $end])
            ->latest('received_at')
            ->take(10)
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
