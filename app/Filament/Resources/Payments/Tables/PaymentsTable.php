<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
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
                    ->label(__('FPPS Number'))
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('testRequest.customer.name')
                    ->label(__('Applicant / Customer'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('amount')
                    ->label(__('Amount Paid'))
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('Status'))
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
                    ->label(__('Payment Time'))
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('recordedBy.name')
                    ->label(__('Cashier Officer'))
                    ->badge()
                    ->color('gray')
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Payment Status'))
                    ->options([
                        'unpaid' => 'Belum Bayar',
                        'paid' => 'Lunas',
                        'refunded' => 'Dikembalikan',
                    ]),
            ])
            ->recordActions([
                Action::make('print_receipt')
                    ->label(__('Cetak Kuitansi'))
                    ->icon(Heroicon::OutlinedPrinter)
                    ->color('info')
                    ->url(fn (Payment $record): string => route('payments.receipt.print', $record))
                    ->openUrlInNewTab(),
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
