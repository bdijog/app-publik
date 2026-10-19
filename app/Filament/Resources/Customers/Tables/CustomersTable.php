<?php

namespace App\Filament\Resources\Customers\Tables;

use App\Enums\CustomerType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('organization_name')
                    ->label('Instansi / Faskes')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer_type')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(function ($state): string {
                        if ($state instanceof CustomerType) {
                            return $state->label();
                        }

                        return match ($state) {
                            'individual' => 'Perorangan',
                            'organization' => 'Instansi',
                            'health_facility' => 'Fasilitas Kesehatan',
                            default => (string) $state,
                        };
                    })
                    ->color(fn ($state): string => match ($state?->value ?? $state) {
                        'individual' => 'gray',
                        'organization' => 'info',
                        'health_facility' => 'success',
                        default => 'primary',
                    }),

                TextColumn::make('phone')
                    ->label('Nomor Telepon')
                    ->searchable(),

                TextColumn::make('test_requests_count')
                    ->counts('testRequests')
                    ->label('Total Permohonan')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('customer_type')
                    ->label('Kategori Pemohon')
                    ->options([
                        'individual' => 'Perorangan',
                        'organization' => 'Instansi / Perusahaan',
                        'health_facility' => 'Fasilitas Kesehatan',
                    ]),
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
