<?php

namespace App\Filament\Resources\DocumentAccessLogs\Tables;

use App\Enums\DocumentAccessAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DocumentAccessLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('testReport.report_number')
                    ->label('Nomor LHU')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('action')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn (DocumentAccessAction|string|null $state): string => match ($state instanceof DocumentAccessAction ? $state->value : $state) {
                        'view' => 'info',
                        'download' => 'success',
                        'print' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (DocumentAccessAction|string|null $state): string => match ($state instanceof DocumentAccessAction ? $state->value : $state) {
                        'view' => 'Lihat / Preview',
                        'download' => 'Unduh Dokumen',
                        'print' => 'Cetak',
                        default => (string) $state,
                    }),

                TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('employee.name')
                    ->label('Pegawai')
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('ip_address')
                    ->label('Alamat IP')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                TextColumn::make('accessed_at')
                    ->label('Waktu Akses')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('accessed_at', 'desc')
            ->filters([
                SelectFilter::make('action')
                    ->label('Jenis Akses')
                    ->options([
                        DocumentAccessAction::View->value => 'Lihat / Preview',
                        DocumentAccessAction::Download->value => 'Unduh Dokumen',
                        DocumentAccessAction::Print->value => 'Cetak',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }
}
