<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Enums\PaymentStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('testRequest.fpps_number')
                    ->label('Nomor FPPS')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('testRequest.customer.name')
                    ->label('Pemohon / Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('amount')
                    ->label('Jumlah Bayar')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state): string => match ($state?->value ?? $state) {
                        'paid', PaymentStatus::Paid => 'success',
                        'refunded', PaymentStatus::Refunded => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(function ($state): string {
                        if ($state instanceof PaymentStatus) {
                            return $state->label();
                        }

                        return match ($state) {
                            'paid' => 'Lunas',
                            'refunded' => 'Dikembalikan',
                            default => 'Belum Bayar',
                        };
                    }),

                TextColumn::make('paid_at')
                    ->label('Waktu Bayar')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('recordedBy.name')
                    ->label('Petugas Kasir')
                    ->badge()
                    ->color('gray')
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pembayaran')
                    ->options([
                        'unpaid' => 'Belum Bayar',
                        'paid' => 'Lunas',
                        'refunded' => 'Dikembalikan',
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
