<?php

namespace App\Filament\Resources\TestReports\Schemas;

use App\Enums\ReportStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Laporan Hasil Uji (LHU)')
                    ->description('Nomor LHU terkontrol, versi terbitan, dan referensi permohonan pengujian.')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('report_number')
                                    ->label('Nomor LHU')
                                    ->placeholder('Otomatis dibuat sistem (LHU-YYYY-XXXXX)')
                                    ->disabled()
                                    ->dehydrated(false),

                                Select::make('test_request_id')
                                    ->relationship('testRequest', 'request_number')
                                    ->label('Permohonan / No. FPPS')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('report_template_id')
                                    ->relationship('template', 'name')
                                    ->label('Format Template LHU')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                TextInput::make('version')
                                    ->label('Versi Terbitan')
                                    ->numeric()
                                    ->default(1)
                                    ->disabled()
                                    ->dehydrated(),

                                Select::make('status')
                                    ->label('Status Dokumen')
                                    ->options(collect(ReportStatus::cases())->pluck('name', 'value'))
                                    ->default(ReportStatus::Draft->value)
                                    ->required(),

                                Select::make('parent_report_id')
                                    ->relationship('parentReport', 'report_number')
                                    ->label('LHU Acuan (Jika Revisi)')
                                    ->disabled(),
                            ]),
                    ]),

                Section::make('Otorisasi & Pengesahan Dokumen')
                    ->description('Daftar personel berwenang yang memeriksa, memvalidasi teknis, dan mengesahkan terbitnya LHU.')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('verifier_id')
                                    ->relationship('verifier', 'name')
                                    ->label('Diverifikasi Oleh (Penyelia/PJ Teknis)')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('validator_id')
                                    ->relationship('validator', 'name')
                                    ->label('Divalidasi Oleh (Penanggung Jawab)')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('issued_by')
                                    ->relationship('issuer', 'name')
                                    ->label('Diterbitkan Oleh (Kepala/Manajer Mutu)')
                                    ->disabled(),

                                DatePicker::make('validated_on')
                                    ->label('Tanggal Validasi Teknis'),

                                DateTimePicker::make('issued_at')
                                    ->label('Waktu Penerbitan Resmi')
                                    ->disabled(),
                            ]),
                    ]),

                Section::make('Kesimpulan Teknis & Berkas PDF')
                    ->schema([
                        Textarea::make('considerations')
                            ->label('Pertimbangan / Kesimpulan Pemenuhan Standar Baku Mutu')
                            ->placeholder('Contoh: Berdasarkan hasil uji parameter fisika & kimia di atas, sampel memenuhi persyaratan air minum sesuai Permenkes...')
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('file_path')
                            ->label('Path Dokumen PDF Terbitan')
                            ->placeholder('reports/lhu-xxxxx.pdf (dihasilkan saat LHU diterbitkan)')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
