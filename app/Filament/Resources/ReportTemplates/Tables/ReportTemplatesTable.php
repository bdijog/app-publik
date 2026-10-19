<?php

namespace App\Filament\Resources\ReportTemplates\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ReportTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Template')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('version')
                    ->label('Versi')
                    ->badge(),

                IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('test_reports_count')
                    ->counts('testReports')
                    ->label('Total LHU Terbit')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('updated_at')
                    ->label('Pembaruan Terakhir')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([]);
    }
}
