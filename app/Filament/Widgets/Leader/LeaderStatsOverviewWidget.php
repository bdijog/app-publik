<?php

namespace App\Filament\Widgets\Leader;

use App\Enums\ReportStatus;
use App\Enums\TestRequestStatus;
use App\Models\Employee;
use App\Models\Sample;
use App\Models\TestReport;
use App\Models\TestRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class LeaderStatsOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = '60s';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && ($user->hasRole('leader') || $user->hasRole('admin'));
    }

    protected function getStats(): array
    {
        $thisMonth = now()->startOfMonth();

        $totalRequestsThisMonth = TestRequest::where('registered_at', '>=', $thisMonth)->count();
        $totalRequests = TestRequest::count();

        $totalSamples = Sample::count();
        $activeSamples = Sample::where('status', 'accepted')->count();

        // SLA Compliance: Permohonan yang selesai tepat waktu
        $completedRequests = TestRequest::where('status', TestRequestStatus::Completed->value)
            ->whereNotNull('estimated_completion_date')
            ->get();

        $onTimeCount = $completedRequests->filter(function (TestRequest $req): bool {
            return $req->updated_at?->format('Y-m-d') <= $req->estimated_completion_date?->format('Y-m-d');
        })->count();

        $slaRate = $completedRequests->isNotEmpty()
            ? round(($onTimeCount / $completedRequests->count()) * 100, 1)
            : 100.0;

        // LHU Final dan Penyerahan
        $finalReportsCount = TestReport::where('status', ReportStatus::Final->value)->count();
        $handedOverCount = TestReport::where('status', ReportStatus::Final->value)
            ->has('handovers')
            ->count();

        return [
            Stat::make('Permohonan Bulan Ini', number_format($totalRequestsThisMonth))
                ->description("Total keseluruhan: {$totalRequests} permohonan")
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('primary'),

            Stat::make('Sampel Diproses', number_format($totalSamples))
                ->description("{$activeSamples} sampel aktif dalam pengujian")
                ->descriptionIcon('heroicon-m-beaker')
                ->color('info'),

            Stat::make('Kepatuhan SLA', "{$slaRate}%")
                ->description('Penyelesaian sesuai target estimasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color($slaRate >= 90 ? 'success' : ($slaRate >= 80 ? 'warning' : 'danger')),

            Stat::make('LHU Terbit & Serah', "{$finalReportsCount} Terbit")
                ->description("{$handedOverCount} telah diserahkan ke pelanggan")
                ->descriptionIcon('heroicon-m-document-check')
                ->color('success'),
        ];
    }
}
