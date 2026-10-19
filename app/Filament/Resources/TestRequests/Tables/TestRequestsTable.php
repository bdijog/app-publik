<?php

namespace App\Filament\Resources\TestRequests\Tables;

use App\Enums\PaymentStatus;
use App\Enums\RegistrationChannel;
use App\Enums\TestRequestStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TestRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fpps_number')
                    ->label('Nomor FPPS')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('customer.name')
                    ->label('Pemohon / Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('serviceGroup.name')
                    ->label('Kelompok Layanan')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('channel')
                    ->label('Kanal')
                    ->badge()
                    ->formatStateUsing(function ($state): string {
                        if ($state instanceof RegistrationChannel) {
                            return $state->label();
                        }

                        return match ($state) {
                            'online' => 'Online',
                            'whatsapp' => 'WhatsApp',
                            'counter' => 'Loket',
                            default => (string) $state,
                        };
                    })
                    ->color('gray'),

                TextColumn::make('total_amount')
                    ->label('Total Retribusi')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),

                TextColumn::make('payment_status')
                    ->label('Pembayaran')
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

                TextColumn::make('status')
                    ->label('Status Alur')
                    ->badge()
                    ->color(fn ($state): string => match ($state?->value ?? $state) {
                        'completed', TestRequestStatus::Completed => 'success',
                        'report_issued', TestRequestStatus::ReportIssued => 'info',
                        'validated', TestRequestStatus::Validated => 'primary',
                        'in_progress', TestRequestStatus::InProgress => 'warning',
                        'cancelled', TestRequestStatus::Cancelled => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(function ($state): string {
                        if ($state instanceof TestRequestStatus) {
                            return $state->label();
                        }

                        return match ($state) {
                            'draft' => 'Draf',
                            'submitted' => 'Menunggu Verifikasi',
                            'awaiting_payment' => 'Menunggu Bayar',
                            'awaiting_sample' => 'Menunggu Sampel',
                            'in_progress' => 'Sedang Uji',
                            'validated' => 'Tervalidasi',
                            'report_issued' => 'LHU Terbit',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                            default => (string) $state,
                        };
                    }),

                TextColumn::make('estimated_completion_date')
                    ->label('Target SLA')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('registered_at')
                    ->label('Waktu Daftar')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('service_group_id')
                    ->label('Kelompok Layanan')
                    ->relationship('serviceGroup', 'name')
                    ->preload(),

                SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'unpaid' => 'Belum Bayar',
                        'paid' => 'Lunas',
                        'refunded' => 'Dikembalikan',
                    ]),

                SelectFilter::make('status')
                    ->label('Status Permohonan')
                    ->options([
                        'submitted' => 'Menunggu Verifikasi',
                        'awaiting_payment' => 'Menunggu Pembayaran',
                        'awaiting_sample' => 'Menunggu Sampel',
                        'in_progress' => 'Sedang Pengujian',
                        'validated' => 'Hasil Tervalidasi',
                        'report_issued' => 'LHU Terbit',
                        'completed' => 'Selesai & Diserahkan',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
