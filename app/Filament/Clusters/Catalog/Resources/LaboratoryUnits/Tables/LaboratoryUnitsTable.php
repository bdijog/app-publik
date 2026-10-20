<?php

namespace App\Filament\Clusters\Catalog\Resources\LaboratoryUnits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LaboratoryUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('Unit Code'))
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label(__('Unit Name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('service_groups_count')
                    ->counts('serviceGroups')
                    ->label(__('Service Group'))
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('employees_count')
                    ->counts('employees')
                    ->label(__('Employee'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),
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
