<?php

namespace App\Filament\Widgets\Admin;

use App\Models\ActivityLog;
use App\Models\DocumentAccessLog;
use App\Models\Employee;
use App\Models\ServiceGroup;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class SystemHealthStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 16;

    protected ?string $pollingInterval = '60s';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && $user->hasRole('admin');
    }

    protected function getStats(): array
    {
        $today = today();

        $activeEmployees = Employee::where('is_active', true)->count();
        $recentLogins = Employee::where('last_login_at', '>=', now()->subHours(24))->count();

        $lhuAccessToday = DocumentAccessLog::whereDate('accessed_at', $today)->count();
        $downloadsToday = DocumentAccessLog::whereDate('accessed_at', $today)
            ->where('action', 'download')
            ->count();

        $activityCountToday = ActivityLog::whereDate('created_at', $today)->count();

        $activeGroups = ServiceGroup::where('is_active', true)->count();

        return [
            Stat::make('Pegawai Terdaftar', number_format($activeEmployees))
                ->description("{$recentLogins} pegawai aktif 24 jam terakhir")
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Akses Dokumen LHU', number_format($lhuAccessToday))
                ->description("{$downloadsToday} unduhan PDF resmi hari ini")
                ->descriptionIcon('heroicon-m-document-arrow-down')
                ->color('info'),

            Stat::make('Aktivitas Sistem', number_format($activityCountToday))
                ->description('Log mutasi data tercatat hari ini')
                ->descriptionIcon('heroicon-m-finger-print')
                ->color('success'),

            Stat::make('Katalog Layanan', number_format($activeGroups))
                ->description('Kelompok layanan aktif beroperasi')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('primary'),
        ];
    }
}
