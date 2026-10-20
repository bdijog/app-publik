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
                    ->label(__('LHU Number'))
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('testReport.testRequest.customer.name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                TextColumn::make('received_by_name')
                    ->label(__('Recipient Name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('handedBy.name')
                    ->label(__('Handover Officer'))
                    ->sortable(),

                TextColumn::make('handed_at')
                    ->label(__('Handover Time'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('notes')
                    ->label(__('Notes / Receipt No.'))
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
