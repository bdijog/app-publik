<?php

namespace App\Filament\Clusters\Catalog\Resources\SampleTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SampleTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Sample Type'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('serviceGroup.name')
                    ->label(__('Service Group'))
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('min_volume')
                    ->label(__('Min. Vol'))
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('container_requirement')
                    ->label(__('Container Requirements Checklist'))
                    ->limit(35)
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('service_group_id')
                    ->label(__('Service Group'))
                    ->relationship('serviceGroup', 'name')
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
