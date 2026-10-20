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
                    ->label(__('LHU Number'))
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('action')
                    ->label(__('Action'))
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
                    ->label(__('Customer'))
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('employee.name')
                    ->label(__('Employee'))
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('ip_address')
                    ->label(__('IP Address'))
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                TextColumn::make('accessed_at')
                    ->label(__('Access Time'))
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('accessed_at', 'desc')
            ->filters([
                SelectFilter::make('action')
                    ->label(__('Access Type'))
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
