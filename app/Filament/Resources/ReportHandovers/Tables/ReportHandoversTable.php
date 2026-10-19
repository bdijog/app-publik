<?php

namespace App\Filament\Resources\ReportHandovers\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReportHandoversTable
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

                TextColumn::make('testReport.testRequest.customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                TextColumn::make('received_by_name')
                    ->label('Nama Penerima')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('handedBy.name')
                    ->label('Petugas Penyerah')
                    ->sortable(),

                TextColumn::make('handed_at')
                    ->label('Waktu Penyerahan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('notes')
                    ->label('Catatan / Resi')
                    ->limit(35)
                    ->tooltip(fn ($record) => $record->notes),
            ])
            ->defaultSort('handed_at', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([]);
    }
}
