<?php

namespace App\Filament\Clusters\Catalog\Resources\CompletionEstimates\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompletionEstimateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konfigurasi Estimasi Waktu Penyelesaian (SLA)')
                    ->description('Pengaturan standar lama waktu pengujian hingga LHU terbit')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('service_group_id')
                                ->label('Kelompok Layanan')
                                ->relationship('serviceGroup', 'name')
                                ->searchable()
                                ->preload()
                                ->nullable()
                                ->helperText('Kosongkan jika berlaku umum untuk seluruh kelompok layanan'),

                            TextInput::make('days')
                                ->label('Jumlah Hari Kalender')
                                ->numeric()
                                ->minValue(1)
                                ->required()
                                ->suffix('Hari')
                                ->helperText('Standar brosur Balai: 10 hari kalender'),

                            Select::make('start_point')
                                ->label('Titik Awal Perhitungan Waktu')
                                ->options([
                                    'payment' => 'Sejak Pembayaran / Registrasi Lunas',
                                    'receipt' => 'Sejak Sampel Fisik Diterima di Lab',
                                ])
                                ->default('payment')
                                ->required(),

                            DatePicker::make('effective_from')
                                ->label('Mulai Berlaku')
                                ->default(now())
                                ->required(),
                        ]),
                    ]),
            ]);
    }
}
