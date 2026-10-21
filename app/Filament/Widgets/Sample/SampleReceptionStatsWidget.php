<?php

namespace App\Filament\Widgets\Sample;

use App\Enums\SampleDecision;
use App\Enums\SampleStatus;
use App\Models\Employee;
use App\Models\Sample;
use App\Models\SampleReceipt;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class SampleReceptionStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 9;

    protected ?string $pollingInterval = '30s';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && ($user->hasRole('sample_receiver') || $user->hasRole('service_officer') || $user->hasRole('admin'));
    }

    protected function getStats(): array
    {
        $today = now()->startOfDay();

        $awaitingCount = Sample::where('status', SampleStatus::Pending->value)->count();

        $acceptedToday = SampleReceipt::where('decision', SampleDecision::Accepted->value)
            ->where('received_at', '>=', $today)
            ->count();

        $rejectedToday = SampleReceipt::where('decision', SampleDecision::Rejected->value)
            ->where('received_at', '>=', $today)
            ->count();

        $totalChecked = $acceptedToday + $rejectedToday;
        $rejectionRate = $totalChecked > 0 ? round(($rejectedToday / $totalChecked) * 100, 1) : 0.0;

        return [
            Stat::make('Antrean Sampel Tiba', number_format($awaitingCount))
                ->description('Sampel fisik menunggu cek kelaikan')
                ->descriptionIcon('heroicon-m-inbox-stack')
                ->color($awaitingCount > 0 ? 'warning' : 'success'),

            Stat::make('Diterima Hari Ini', number_format($acceptedToday))
                ->description('Lolos syarat dan diteruskan ke lab')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Ditolak Hari Ini', number_format($rejectedToday))
                ->description('Tidak memenuhi syarat wadah/volume')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color($rejectedToday > 0 ? 'danger' : 'gray'),

            Stat::make('Tingkat Penolakan', "{$rejectionRate}%")
                ->description('Rasio penolakan sampel hari ini')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color($rejectionRate > 10 ? 'danger' : 'primary'),
        ];
    }
}
