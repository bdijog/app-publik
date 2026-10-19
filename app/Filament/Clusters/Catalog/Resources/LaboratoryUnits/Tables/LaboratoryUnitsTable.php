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
                    ->label('Kode Instalasi')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nama Instalasi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('service_groups_count')
                    ->counts('serviceGroups')
                    ->label('Kelompok Layanan')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('employees_count')
                    ->counts('employees')
                    ->label('Pegawai')
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
