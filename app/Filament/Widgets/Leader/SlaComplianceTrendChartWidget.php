<?php

namespace App\Filament\Widgets\Leader;

use App\Enums\TestRequestStatus;
use App\Models\Employee;
use App\Models\TestRequest;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class SlaComplianceTrendChartWidget extends ChartWidget
{
    protected ?string $heading = 'Tren Volume Permohonan & Penyelesaian (6 Bulan Terakhir)';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'half';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && ($user->hasRole('leader') || $user->hasRole('admin'));
    }

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(function (int $i): Carbon {
            return now()->subMonths($i);
        });

        $labels = $months->map(fn (Carbon $m) => $m->translatedFormat('M Y'))->toArray();

        $incoming = [];
        $completed = [];

        foreach ($months as $month) {
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $incoming[] = TestRequest::whereBetween('registered_at', [$start, $end])->count();
            $completed[] = TestRequest::where('status', TestRequestStatus::Completed->value)
                ->whereBetween('updated_at', [$start, $end])
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Permohonan Masuk',
                    'data' => $incoming,
                    'borderColor' => '#0D9488',
                    'backgroundColor' => 'rgba(13, 148, 136, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Permohonan Selesai',
                    'data' => $completed,
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
