<?php

namespace App\Filament\Widgets\Sample;

use App\Enums\SampleDecision;
use App\Models\Employee;
use App\Models\SampleReceipt;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SampleRejectionReasonChartWidget extends ChartWidget
{
    protected ?string $heading = 'Analisis Alasan Penolakan Sampel (Bulan Ini)';

    protected static ?int $sort = 11;

    protected int|string|array $columnSpan = 'half';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && ($user->hasRole('sample_receiver') || $user->hasRole('service_officer') || $user->hasRole('admin'));
    }

    protected function getData(): array
    {
        $startOfMonth = now()->startOfMonth();

        $rejections = SampleReceipt::where('decision', SampleDecision::Rejected->value)
            ->where('received_at', '>=', $startOfMonth)
            ->whereNotNull('rejection_reason')
            ->selectRaw('rejection_reason, count(*) as count')
            ->groupBy('rejection_reason')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        if ($rejections->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => 'Sampel Ditolak',
                        'data' => [0],
                        'backgroundColor' => ['#10B981'],
                    ],
                ],
                'labels' => ['Tidak ada penolakan bulan ini'],
            ];
        }

        $labels = $rejections->pluck('rejection_reason')->map(fn ($r) => Str::limit($r, 25))->toArray();
        $counts = $rejections->pluck('count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Sampel Ditolak',
                    'data' => $counts,
                    'backgroundColor' => ['#EF4444', '#F97316', '#F59E0B', '#64748B', '#8B5CF6'],
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
