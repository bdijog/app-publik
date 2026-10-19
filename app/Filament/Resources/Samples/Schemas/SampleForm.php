<?php

namespace App\Filament\Resources\Samples\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SampleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Sampel Fisik')
                    ->description('Data sampel laboratorium yang terikat dengan nomor permohonan FPPS')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('test_request_id')
                                ->label('Nomor FPPS Permohonan')
                                ->relationship('testRequest', 'fpps_number')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('sample_type_id')
                                ->label('Kategori / Jenis Sampel')
                                ->relationship('sampleType', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            TextInput::make('sample_code')
                                ->label('Kode Sampel Balai')
                                ->placeholder('Dibuat otomatis oleh sistem')
                                ->disabled()
                                ->dehydrated(),

                            TextInput::make('sample_name')
                                ->label('Nama / Uraian Sampel')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('mis. Air Baku Reservoir / Makanan Olahan Ikan'),

                            TextInput::make('quantity')
                                ->label('Jumlah / Volume Diserahkan')
                                ->numeric()
                                ->required()
                                ->placeholder('mis. 1000'),

                            TextInput::make('unit')
                                ->label('Satuan')
                                ->default('mL')
                                ->required()
                                ->maxLength(20)
                                ->placeholder('mis. mL, Gram, Botol'),

                            DateTimePicker::make('collected_at')
                                ->label('Waktu Pengambilan Sampel')
                                ->nullable(),

                            Select::make('status')
                                ->label('Status Sampel')
                                ->options([
                                    'pending' => 'Menunggu Penerimaan',
                                    'accepted' => 'Diterima di Lab',
                                    'rejected' => 'Ditolak',
                                ])
                                ->default('pending')
                                ->required(),
                        ]),
                    ]),
            ]);
    }
}
