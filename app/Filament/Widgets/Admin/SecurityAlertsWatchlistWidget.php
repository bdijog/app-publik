<?php

namespace App\Filament\Widgets\Admin;

use App\Models\ActivityLog;
use App\Models\Employee;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class SecurityAlertsWatchlistWidget extends TableWidget
{
    protected static ?int $sort = 18;

    protected int|string|array $columnSpan = 'half';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && $user->hasRole('admin');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Aktivitas Mutasi Data & Jejak Sistem Terkini')
            ->query(
                ActivityLog::query()
                    ->latest('created_at')
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M H:i:s')
                    ->sortable(),

                TextColumn::make('action')
                    ->label('Tindakan')
                    ->badge()
                    ->color(fn ($state): string => match (strtolower((string) ($state?->value ?? $state))) {
                        'create', 'store', 'insert' => 'success',
                        'update', 'edit' => 'warning',
                        'delete', 'destroy' => 'danger',
                        default => 'info',
                    }),

                TextColumn::make('subject_type')
                    ->label('Entitas Data')
                    ->formatStateUsing(fn ($state) => class_basename((string) $state))
                    ->badge(),

                TextColumn::make('causer_type')
                    ->label('Pelaku')
                    ->formatStateUsing(fn ($state, $record) => class_basename((string) $state)." #{$record->causer_id}"),
            ])
            ->paginated([5, 10]);
    }
}
