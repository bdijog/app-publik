<?php

namespace App\Filament\Clusters\Catalog\Resources\TestParameters\Tables;

use App\Enums\ResultType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TestParametersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Test Parameter'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('serviceGroup.name')
                    ->label(__('Service Group'))
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('unit')
                    ->label(__('Unit'))
                    ->placeholder('-'),

                TextColumn::make('method')
                    ->label(__('Default Method'))
                    ->limit(25)
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('result_type')
                    ->label(__('Type'))
                    ->badge()
                    ->formatStateUsing(function ($state): string {
                        if ($state instanceof ResultType) {
                            return $state->label();
                        }

                        return $state === 'numeric' ? 'Angka' : 'Teks';
                    })
                    ->color(fn ($state) => ($state === 'numeric' || $state === ResultType::Numeric) ? 'info' : 'gray'),

                TextColumn::make('threshold')
                    ->label(__('Quality Standard'))
                    ->state(function ($record): string {
                        $isNumeric = ($record->result_type === ResultType::Numeric || $record->result_type?->value === 'numeric' || $record->result_type === 'numeric');
                        if ($isNumeric) {
                            if (filled($record->threshold_min) && filled($record->threshold_max)) {
                                return "{$record->threshold_min} - {$record->threshold_max}";
                            }
                            if (filled($record->threshold_max)) {
                                return "≤ {$record->threshold_max}";
                            }
                            if (filled($record->threshold_min)) {
                                return "≥ {$record->threshold_min}";
                            }
                        }

                        return $record->threshold_text ?? '-';
                    }),

                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('service_group_id')
                    ->label(__('Service Group'))
                    ->relationship('serviceGroup', 'name')
                    ->preload(),

                SelectFilter::make('result_type')
                    ->label(__('Result Type'))
                    ->options([
                        'numeric' => 'Angka / Numerik',
                        'text' => 'Teks / Kualitatif',
                    ]),

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
