<?php

namespace App\Filament\Clusters\Catalog\Resources\Tariffs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TariffsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('effective_from', 'desc')
            ->columns([
                TextColumn::make('item_name')
                    ->label('Objek Layanan')
                    ->state(fn ($record): string => $record->testParameter?->name
                        ?? $record->servicePackage?->name
                        ?? $record->serviceGroup?->name
                        ?? '-')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('testParameter', fn ($q) => $q->where('name', 'ilike', "%{$search}%"))
                            ->orWhereHas('servicePackage', fn ($q) => $q->where('name', 'ilike', "%{$search}%"))
                            ->orWhereHas('serviceGroup', fn ($q) => $q->where('name', 'ilike', "%{$search}%"));
                    })
                    ->weight('bold'),

                TextColumn::make('category')
                    ->label('Tingkat Objek')
                    ->badge()
                    ->state(fn ($record): string => $record->test_parameter_id ? 'Parameter' : ($record->service_package_id ? 'Paket' : 'Kelompok'))
                    ->color(fn ($state): string => match ($state) {
                        'Parameter' => 'info',
                        'Paket' => 'warning',
                        default => 'primary',
                    }),

                TextColumn::make('amount')
                    ->label('Nominal Tarif')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),

                TextColumn::make('effective_from')
                    ->label('Mulai Berlaku')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('effective_until')
                    ->label('Berakhir')
                    ->date('d M Y')
                    ->placeholder('Masih Berlaku')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('currently_effective')
                    ->label('Hanya yang Masih Berlaku')
                    ->query(fn (Builder $query) => $query->where('effective_from', '<=', now())
                        ->where(fn ($q) => $q->whereNull('effective_until')->orWhere('effective_until', '>=', now()))),
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
