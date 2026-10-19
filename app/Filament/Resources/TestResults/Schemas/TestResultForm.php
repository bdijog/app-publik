<?php

namespace App\Filament\Resources\TestResults\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestResultForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pekerjaan Uji')
                    ->description('Kaitan hasil uji dengan SPK dan parameter yang diuji')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('test_job_id')
                                ->label('Nomor SPK Pengujian')
                                ->relationship('testJob', 'job_number')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('test_request_parameter_id')
                                ->label('Parameter Pengujian')
                                ->relationship('testRequestParameter.testParameter', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('analyst_id')
                                ->label('Analis Pelaksana')
                                ->relationship('analyst', 'name')
                                ->searchable()
                                ->preload()
                                ->default(fn () => auth('employee')->id())
                                ->required(),
                        ]),
                    ]),

                Section::make('Nilai Pengukuran Laboratorium')
                    ->description('Input angka atau teks hasil pengukuran sesuai baku mutu parameter')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('value_numeric')
                                ->label('Nilai Hasil (Numerik / Angka)')
                                ->numeric()
                                ->placeholder('mis. 7.45'),

                            TextInput::make('value_text')
                                ->label('Nilai Hasil (Kualitatif / Teks)')
                                ->placeholder('mis. Negatif / Tidak Berbau / Jernih')
                                ->maxLength(255),

                            TextInput::make('unit')
                                ->label('Satuan Pengukuran')
                                ->maxLength(30)
                                ->placeholder('mis. mg/L, CFU/mL, NTU'),

                            TextInput::make('method')
                                ->label('Metode yang Digunakan')
                                ->maxLength(255)
                                ->placeholder('mis. Spektrofotometri / Titrimetri / SNI'),

                            DatePicker::make('tested_at')
                                ->label('Tanggal Pengujian')
                                ->default(now())
                                ->required(),

                            Select::make('status')
                                ->label('Status Hasil')
                                ->options([
                                    'draft' => 'Draf Hasil',
                                    'submitted' => 'Menunggu Validasi',
                                    'returned' => 'Dikembalikan untuk Perbaikan',
                                    'validated' => 'Tervalidasi',
                                ])
                                ->default('draft')
                                ->required(),

                            Textarea::make('notes')
                                ->label('Catatan Analis / Blanko / QC')
                                ->rows(2)
                                ->columnSpanFull(),

                            Toggle::make('is_locked')
                                ->label('Status Terkunci (Locked)')
                                ->helperText('Hasil terkunci tidak dapat diubah kecuali melalui mekanisme revisi resmi')
                                ->disabled()
                                ->dehydrated(),
                        ]),
                    ]),
            ]);
    }
}
