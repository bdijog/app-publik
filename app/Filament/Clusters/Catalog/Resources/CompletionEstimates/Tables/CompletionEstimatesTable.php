<?php

namespace App\Filament\Clusters\Catalog\Resources\CompletionEstimates\Tables;

use App\Enums\EstimateStartPoint;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompletionEstimatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('serviceGroup.name')
                    ->label(__('Service Group'))
                    ->placeholder('Semua Kelompok (Default Balai)')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('days')
                    ->label(__('Estimated Completion'))
                    ->suffix(' Hari Kalender')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('start_point')
                    ->label(__('Start Point'))
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        EstimateStartPoint::Payment, 'payment' => 'info',
                        default => 'success',
                    })
                    ->formatStateUsing(function ($state): string {
                        if ($state instanceof EstimateStartPoint) {
                            return $state->label();
                        }

                        return $state === 'payment' ? 'Sejak Pembayaran' : 'Sejak Penerimaan Sampel';
                    }),

                TextColumn::make('effective_from')
                    ->label(__('Effective From'))
                    ->date('d M Y')
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
