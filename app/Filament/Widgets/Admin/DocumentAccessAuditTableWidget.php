<?php

namespace App\Filament\Widgets\Admin;

use App\Enums\DocumentAccessAction;
use App\Models\DocumentAccessLog;
use App\Models\Employee;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class DocumentAccessAuditTableWidget extends TableWidget
{
    protected static ?int $sort = 17;

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
            ->heading('Audit Trail Akses & Unduh Dokumen LHU')
            ->query(
                DocumentAccessLog::query()
                    ->with(['testReport', 'customer', 'employee'])
                    ->latest('accessed_at')
            )
            ->columns([
                TextColumn::make('accessed_at')
                    ->label('Waktu')
                    ->dateTime('d M H:i')
                    ->sortable(),

                TextColumn::make('testReport.report_number')
                    ->label('Nomor LHU')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                TextColumn::make('action')
                    ->label('Aksi')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state instanceof DocumentAccessAction ? $state->label() : (DocumentAccessAction::tryFrom((string) $state)?->label() ?? (string) $state))
                    ->color(fn ($state): string => match ($state instanceof DocumentAccessAction ? $state->value : (string) $state) {
                        'download' => 'success',
                        'print' => 'warning',
                        'view' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('accessed_by')
                    ->label('Pengakses')
                    ->state(function (DocumentAccessLog $record): string {
                        if ($record->customer) {
                            return "Pelanggan: {$record->customer->name}";
                        }
                        if ($record->employee) {
                            return "Pegawai: {$record->employee->name}";
                        }

                        return 'Publik';
                    })
                    ->limit(20),

                TextColumn::make('ip_address')
                    ->label('Alamat IP')
                    ->limit(15),
            ])
            ->paginated([5, 10]);
    }
}
