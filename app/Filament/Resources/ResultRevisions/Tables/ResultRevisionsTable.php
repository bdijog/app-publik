<?php

namespace App\Filament\Resources\ResultRevisions\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResultRevisionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('testResult.testJob.job_number')
                    ->label('No. SPK')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('testResult.testParameter.name')
                    ->label('Parameter')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('old_value')
                    ->label('Nilai Lama')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('new_value')
                    ->label('Nilai Baru')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('reason')
                    ->label('Alasan Revisi')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->reason),

                TextColumn::make('revisedBy.name')
                    ->label('Direvisi Oleh')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Waktu Revisi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }
}
