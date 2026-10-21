<?php

namespace App\Filament\Widgets\Lab;

use App\Enums\TestJobStatus;
use App\Enums\TestResultStatus;
use App\Models\Employee;
use App\Models\TestJob;
use App\Models\TestResult;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class LabWorkstationStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 12;

    protected ?string $pollingInterval = '30s';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && ($user->hasRole('lab_officer') || $user->hasRole('admin'));
    }

    protected function getStats(): array
    {
        $activeJobs = TestJob::where('status', TestJobStatus::InProgress->value)->count();

        $awaitingValidation = TestResult::where('status', TestResultStatus::Submitted->value)->count();

        $returnedResults = TestResult::where('status', TestResultStatus::Returned->value)->count();

        $validatedCount = TestResult::where('status', TestResultStatus::Validated->value)
            ->whereDate('updated_at', today())
            ->count();

        return [
            Stat::make('SPK Aktif di Lab', number_format($activeJobs))
                ->description('Surat tugas pengujian berjalan')
                ->descriptionIcon('heroicon-m-beaker')
                ->color('primary'),

            Stat::make('Menunggu Validasi', number_format($awaitingValidation))
                ->description('Review teknis Kepala Instalasi')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color($awaitingValidation > 0 ? 'warning' : 'success'),

            Stat::make('Perlu Koreksi (Retur)', number_format($returnedResults))
                ->description('Hasil dikembalikan untuk perbaikan')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color($returnedResults > 0 ? 'danger' : 'gray'),

            Stat::make('Disahkan Hari Ini', number_format($validatedCount))
                ->description('Hasil uji selesai divalidasi hari ini')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('success'),
        ];
    }
}
