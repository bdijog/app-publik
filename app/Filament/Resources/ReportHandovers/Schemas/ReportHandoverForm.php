<?php

namespace App\Filament\Resources\ReportHandovers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReportHandoverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tanda Bukti Penyerahan Dokumen LHU')
                    ->description('Pencatatan serah terima fisik atau pengiriman dokumen resmi hasil pengujian laboratorium.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('test_report_id')
                                    ->relationship('testReport', 'report_number')
                                    ->label('Nomor LHU')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('handed_by')
                                    ->relationship('handedBy', 'name')
                                    ->label('Petugas Penyerah (Front Office)')
                                    ->default(fn () => auth()->id())
                                    ->required(),

                                TextInput::make('received_by_name')
                                    ->label('Nama Penerima / Kuasa Pengambil')
                                    ->placeholder('Nama lengkap orang yang menerima LHU')
                                    ->required()
                                    ->maxLength(255),

                                DateTimePicker::make('handed_at')
                                    ->label('Waktu Penyerahan')
                                    ->default(now())
                                    ->required(),

                                Textarea::make('notes')
                                    ->label('Catatan Penyerahan / Ekspedisi')
                                    ->placeholder('Misal: Diambil langsung dan menandatangani buku ekspedisi / Dikirim kurir...')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
