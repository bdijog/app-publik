<?php

namespace App\Filament\Clusters\Catalog\Resources\ServicePackages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ServicePackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('serviceGroup.name')
                    ->label('Kelompok Layanan')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('parameters_count')
                    ->counts('parameters')
                    ->label('Jumlah Parameter')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('service_group_id')
                    ->label('Kelompok Layanan')
                    ->relationship('serviceGroup', 'name')
                    ->preload(),

                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
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
