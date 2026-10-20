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
                                    ->label(__('LHU Number'))
                                    ->placeholder('Otomatis dibuat sistem (LHU-YYYY-XXXXX)')
                                    ->disabled()
                                    ->dehydrated(false),

                                Select::make('test_request_id')
                                    ->relationship('testRequest', 'request_number')
                                    ->label(__('Request / FPPS No.'))
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('report_template_id')
                                    ->relationship('template', 'name')
                                    ->label(__('LHU Template Format'))
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                TextInput::make('version')
                                    ->label(__('Published Version'))
                                    ->numeric()
                                    ->default(1)
                                    ->disabled()
                                    ->dehydrated(),

                                Select::make('status')
                                    ->label(__('Document Status'))
                                    ->options(collect(ReportStatus::cases())->pluck('name', 'value'))
                                    ->default(ReportStatus::Draft->value)
                                    ->required(),

                                Select::make('parent_report_id')
                                    ->relationship('parentReport', 'report_number')
                                    ->label(__('Reference LHU (If Revision)'))
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
                                    ->label(__('Verified By (Supervisor/Technical PIC)'))
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('validator_id')
                                    ->relationship('validator', 'name')
                                    ->label(__('Validated By (Person in Charge)'))
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('issued_by')
                                    ->relationship('issuer', 'name')
                                    ->label(__('Issued By (Head/Quality Manager)'))
                                    ->disabled(),

                                DatePicker::make('validated_on')
                                    ->label(__('Technical Validation Date')),

                                DateTimePicker::make('issued_at')
                                    ->label(__('Official Issuance Time'))
                                    ->disabled(),
                            ]),
                    ]),

                Section::make('Kesimpulan Teknis & Berkas PDF')
                    ->schema([
                        Textarea::make('considerations')
                            ->label(__('Quality Standard Compliance Consideration / Conclusion'))
                            ->placeholder('Contoh: Berdasarkan hasil uji parameter fisika & kimia di atas, sampel memenuhi persyaratan air minum sesuai Permenkes...')
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('file_path')
                            ->label(__('Published PDF Document Path'))
                            ->placeholder('reports/lhu-xxxxx.pdf (dihasilkan saat LHU diterbitkan)')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
