<?php

namespace App\Filament\Widgets\Lab;

use App\Enums\TestJobStatus;
use App\Models\Employee;
use App\Models\LaboratoryUnit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class TestJobWorkloadChartWidget extends ChartWidget
{
    protected ?string $heading = 'Beban Kerja SPK Pengujian per Instalasi Laboratorium';

    protected static ?int $sort = 13;

    protected int|string|array $columnSpan = 'half';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && ($user->hasRole('lab_officer') || $user->hasRole('admin'));
    }

    protected function getData(): array
    {
        $units = LaboratoryUnit::withCount([
            'testJobs as in_progress_count' => fn ($q) => $q->where('status', TestJobStatus::InProgress->value),
            'testJobs as pending_count' => fn ($q) => $q->where('status', TestJobStatus::Pending->value),
        ])->get();

        $labels = $units->pluck('name')->toArray();
        $inProgress = $units->pluck('in_progress_count')->toArray();
        $pending = $units->pluck('pending_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Sedang Dikerjakan',
                    'data' => $inProgress,
                    'backgroundColor' => '#0D9488',
                ],
                [
                    'label' => 'Menunggu Pengerjaan',
                    'data' => $pending,
                    'backgroundColor' => '#F59E0B',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
