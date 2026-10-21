<?php

namespace App\Filament\Widgets\Leader;

use App\Enums\TestRequestStatus;
use App\Models\Employee;
use App\Models\TestRequest;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class SlaOverdueWatchlistTableWidget extends TableWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && ($user->hasRole('leader') || $user->hasRole('admin'));
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Pengawasan Batas Waktu Layanan (Mendekati / Melewati SLA)')
            ->query(
                TestRequest::query()
                    ->whereNotIn('status', [TestRequestStatus::Completed->value, TestRequestStatus::Cancelled->value])
                    ->whereNotNull('estimated_completion_date')
                    ->orderBy('estimated_completion_date', 'asc')
            )
            ->columns([
                TextColumn::make('fpps_number')
                    ->label('Nomor FPPS')
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('serviceGroup.name')
                    ->label('Kelompok Layanan')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status Terkini')
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_object($state) && method_exists($state, 'label') ? $state->label() : ucfirst(str_replace('_', ' ', (string) $state))),

                TextColumn::make('registered_at')
                    ->label('Tanggal Registrasi')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('estimated_completion_date')
                    ->label('Target Selesai')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('sla_status')
                    ->label('Status SLA')
                    ->badge()
                    ->state(function (TestRequest $record): string {
                        $target = $record->estimated_completion_date;
                        if (! $target) {
                            return 'Belum Ditetapkan';
                        }
                        $diff = now()->startOfDay()->diffInDays($target->startOfDay(), false);
                        if ($diff < 0) {
                            return 'Overdue (+'.abs((int) $diff).' hari)';
                        }
                        if ($diff === 0) {
                            return 'Jatuh Tempo Hari Ini';
                        }

                        return "Sisa {$diff} hari";
                    })
                    ->color(function (TestRequest $record): string {
                        $target = $record->estimated_completion_date;
                        if (! $target) {
                            return 'gray';
                        }
                        $diff = now()->startOfDay()->diffInDays($target->startOfDay(), false);
                        if ($diff < 0) {
                            return 'danger';
                        }
                        if ($diff <= 2) {
                            return 'warning';
                        }

                        return 'success';
                    }),
            ])
            ->paginated([5, 10]);
    }
}
