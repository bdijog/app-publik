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
                    ->label('Jenis Sampel')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('serviceGroup.name')
                    ->label('Kelompok Layanan')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('min_volume')
                    ->label('Vol. Min')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('container_requirement')
                    ->label('Syarat Wadah')
                    ->limit(35)
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('service_group_id')
                    ->label('Kelompok Layanan')
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
