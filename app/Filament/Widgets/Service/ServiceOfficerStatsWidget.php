<?php

namespace App\Filament\Widgets\Service;

use App\Enums\PaymentStatus;
use App\Enums\ReportStatus;
use App\Enums\TestRequestStatus;
use App\Models\Employee;
use App\Models\TestReport;
use App\Models\TestRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ServiceOfficerStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 5;

    protected ?string $pollingInterval = '30s';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && ($user->hasRole('service_officer') || $user->hasRole('admin'));
    }

    protected function getStats(): array
    {
        $today = now()->startOfDay();

        $newToday = TestRequest::where('registered_at', '>=', $today)->count();

        $unverified = TestRequest::whereIn('status', [
            TestRequestStatus::Draft->value,
            TestRequestStatus::Submitted->value,
        ])->count();

        $pendingPayment = TestRequest::where('payment_status', PaymentStatus::Unpaid->value)->count();

        $readyForHandover = TestReport::where('status', ReportStatus::Final->value)
            ->whereDoesntHave('handovers')
            ->count();

        return [
            Stat::make('Permohonan Hari Ini', number_format($newToday))
                ->description('Pendaftaran online, WA, & loket')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('primary'),

            Stat::make('Menunggu Verifikasi', number_format($unverified))
                ->description('Pemeriksaan kelengkapan berkas')
                ->descriptionIcon('heroicon-m-document-magnifying-glass')
                ->color($unverified > 0 ? 'warning' : 'success'),

            Stat::make('Menunggu Pembayaran', number_format($pendingPayment))
                ->description('Tagihan permohonan belum lunas')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color($pendingPayment > 0 ? 'amber' : 'gray'),

            Stat::make('LHU Siap Diserahkan', number_format($readyForHandover))
                ->description('LHU final siap serah fisik/kurir')
                ->descriptionIcon('heroicon-m-gift')
                ->color('success'),
        ];
    }
}
