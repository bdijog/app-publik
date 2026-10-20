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
                    ->label(__('SPK No.'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('testResult.testParameter.name')
                    ->label(__('Parameter'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('old_value')
                    ->label(__('Old Value'))
                    ->badge()
                    ->color('gray'),

                TextColumn::make('new_value')
                    ->label(__('New Value'))
                    ->badge()
                    ->color('warning'),

                TextColumn::make('reason')
                    ->label(__('Revision Reason'))
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->reason),

                TextColumn::make('revisedBy.name')
                    ->label(__('Revised By'))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('Revision Time'))
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
