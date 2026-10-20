<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Models\TestRequest;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pencatatan Pembayaran Retribusi')
                    ->description('Pencatatan bukti dan verifikasi pembayaran layanan pengujian sampel')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('test_request_id')
                                ->label(__('Request FPPS Number'))
                                ->relationship('testRequest', 'fpps_number')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, Set $set) {
                                    if ($state) {
                                        $req = TestRequest::find($state);
                                        if ($req) {
                                            $set('amount', (float) $req->total_amount);
                                        }
                                    }
                                }),

                            TextInput::make('amount')
                                ->label(__('Payment Amount'))
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->minValue(0),

                            Select::make('status')
                                ->label(__('Payment Status'))
                                ->options([
                                    'unpaid' => 'Belum Bayar',
                                    'paid' => 'Lunas',
                                    'refunded' => 'Dikembalikan',
                                ])
                                ->default('paid')
                                ->required(),

                            DateTimePicker::make('paid_at')
                                ->label(__('Payment Received Time'))
                                ->default(now())
                                ->required(),
                        ]),
                    ]),
            ]);
    }
}
