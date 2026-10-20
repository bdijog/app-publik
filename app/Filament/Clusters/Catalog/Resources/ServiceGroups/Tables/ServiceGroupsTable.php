<?php

namespace App\Filament\Clusters\Catalog\Resources\ServiceGroups\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ServiceGroupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('Code'))
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label(__('Service Group'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('laboratoryUnit.name')
                    ->label(__('Responsible Unit'))
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('test_parameters_count')
                    ->counts('testParameters')
                    ->label(__('Parameter'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('service_packages_count')
                    ->counts('servicePackages')
                    ->label(__('Package'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('laboratory_unit_id')
                    ->label(__('Laboratory Unit'))
                    ->relationship('laboratoryUnit', 'name')
                    ->preload(),

                TernaryFilter::make('is_active')
                    ->label(__('Active Status')),
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
