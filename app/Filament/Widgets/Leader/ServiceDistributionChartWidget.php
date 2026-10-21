<?php

namespace App\Filament\Widgets\Leader;

use App\Models\Employee;
use App\Models\ServiceGroup;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ServiceDistributionChartWidget extends ChartWidget
{
    protected ?string $heading = 'Distribusi Permohonan per Kelompok Layanan';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'half';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && ($user->hasRole('leader') || $user->hasRole('admin'));
    }

    protected function getData(): array
    {
        $groups = ServiceGroup::withCount('testRequests')
            ->orderByDesc('test_requests_count')
            ->limit(10)
            ->get();

        $labels = $groups->pluck('name')->toArray();
        $counts = $groups->pluck('test_requests_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Permohonan',
                    'data' => $counts,
                    'backgroundColor' => [
                        '#0D9488', '#0284C7', '#10B981', '#F59E0B', '#6366F1',
                        '#EC4899', '#8B5CF6', '#14B8A6', '#F97316', '#64748B',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
