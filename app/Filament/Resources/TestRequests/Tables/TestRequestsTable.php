<?php

namespace App\Filament\Resources\TestRequests\Tables;

use App\Enums\PaymentStatus;
use App\Enums\RegistrationChannel;
use App\Enums\TestRequestStatus;
use App\Models\TestRequest;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
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
                    ->label(__('FPPS Number'))
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('customer.name')
                    ->label(__('Applicant / Customer'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('serviceGroup.name')
                    ->label(__('Service Group'))
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('channel')
                    ->label(__('Channel'))
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
                    ->label(__('Total Retribution'))
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),

                TextColumn::make('payment_status')
                    ->label(__('Payment'))
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
                    ->label(__('Workflow Status'))
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
                    ->label(__('Target SLA'))
                    ->date('d M Y')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('registered_at')
                    ->label(__('Registration Time'))
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('service_group_id')
                    ->label(__('Service Group'))
                    ->relationship('serviceGroup', 'name')
                    ->preload(),

                SelectFilter::make('payment_status')
                    ->label(__('Payment Status'))
                    ->options([
                        'unpaid' => 'Belum Bayar',
                        'paid' => 'Lunas',
                        'refunded' => 'Dikembalikan',
                    ]),

                SelectFilter::make('status')
                    ->label(__('Request Status'))
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
                Action::make('print_receipt')
                    ->label(__('Cetak FPPS'))
                    ->icon(Heroicon::OutlinedPrinter)
                    ->color('info')
                    ->url(fn (TestRequest $record): string => route('requests.receipt.print', $record))
                    ->openUrlInNewTab(),
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
