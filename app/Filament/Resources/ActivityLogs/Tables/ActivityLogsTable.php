<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('action')
                    ->label(__('Activity / Event'))
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('causer_type')
                    ->label(__('Performer'))
                    ->formatStateUsing(fn ($state, $record) => $state ? class_basename($state)." #{$record->causer_id}" : '-')
                    ->searchable(),

                TextColumn::make('subject_type')
                    ->label(__('Target Object'))
                    ->formatStateUsing(fn ($state, $record) => $state ? class_basename($state)." #{$record->subject_id}" : '-')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label(__('Event Time'))
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }
}
