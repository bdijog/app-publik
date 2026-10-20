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
                                    ->label(__('LHU Number'))
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('handed_by')
                                    ->relationship('handedBy', 'name')
                                    ->label(__('Handover Officer (Front Office)'))
                                    ->default(fn () => auth()->id())
                                    ->required(),

                                TextInput::make('received_by_name')
                                    ->label(__('Recipient / Authorized Pickup Name'))
                                    ->placeholder('Nama lengkap orang yang menerima LHU')
                                    ->required()
                                    ->maxLength(255),

                                DateTimePicker::make('handed_at')
                                    ->label(__('Handover Time'))
                                    ->default(now())
                                    ->required(),

                                Textarea::make('notes')
                                    ->label(__('Handover Notes / Expedition'))
                                    ->placeholder('Misal: Diambil langsung dan menandatangani buku ekspedisi / Dikirim kurir...')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
