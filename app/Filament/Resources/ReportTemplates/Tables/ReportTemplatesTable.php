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
                    ->label(__('Template Name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('version')
                    ->label(__('Version'))
                    ->badge(),

                IconColumn::make('is_active')
                    ->label(__('Active Status'))
                    ->boolean()
                    ->sortable(),

                TextColumn::make('test_reports_count')
                    ->counts('testReports')
                    ->label(__('Total Issued LHU'))
                    ->badge()
                    ->color('gray'),

                TextColumn::make('updated_at')
                    ->label(__('Last Update'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('Active Status')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([]);
    }
}
