<?php

namespace App\Filament\Resources\TestRequests\Schemas;

use App\Models\Tariff;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class TestRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Permohonan & Pemohon')
                    ->description('Data nomor permohonan, kanal pendaftaran, dan informasi pemohon layanan')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('fpps_number')
                                ->label('Nomor FPPS')
                                ->placeholder('Dibuat otomatis oleh sistem')
                                ->disabled()
                                ->dehydrated(),

                            Select::make('channel')
                                ->label('Kanal Pendaftaran')
                                ->options([
                                    'counter' => 'Loket Pelayanan',
                                    'whatsapp' => 'Bantuan WhatsApp',
                                    'online' => 'Pendaftaran Online Mandiri',
                                ])
                                ->default('counter')
                                ->required(),

                            Select::make('customer_id')
                                ->label('Pelanggan / Pemohon')
                                ->relationship('customer', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('purpose')
                                ->label('Tujuan Pemeriksaan')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('mis. Pengawasan Mutu Lingkungan / Syarat Izin Edar / Penelitian'),

                            DateTimePicker::make('registered_at')
                                ->label('Waktu Registrasi')
                                ->default(now())
                                ->required(),
                        ]),
                    ]),

                Section::make('Layanan & Parameter Uji yang Dipesan')
                    ->description('Pemilihan kelompok layanan dan rincian parameter pengujian beserta snapshot tarif')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('service_group_id')
                                ->label('Kelompok Layanan')
                                ->relationship('serviceGroup', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Set $set) => $set('service_package_id', null)),

                            Select::make('service_package_id')
                                ->label('Paket Pemeriksaan (Opsional)')
                                ->relationship('servicePackage', 'name', fn (Builder $query, Get $get) => $query->when(
                                    $get('service_group_id'),
                                    fn ($q, $groupId) => $q->where('service_group_id', $groupId)
                                ))
                                ->searchable()
                                ->preload()
                                ->nullable()
                                ->live(),
                        ]),

                        Repeater::make('parameters')
                            ->relationship('parameters')
                            ->label('Daftar Parameter Uji yang Dipesan')
                            ->schema([
                                Select::make('test_parameter_id')
                                    ->label('Parameter Uji')
                                    ->relationship('testParameter', 'name', fn (Builder $query, Get $get) => $query->when(
                                        $get('../../service_group_id'),
                                        fn ($q, $groupId) => $q->where('service_group_id', $groupId)
                                    ))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        if ($state) {
                                            $tariff = Tariff::where('test_parameter_id', $state)
                                                ->where('effective_from', '<=', now())
                                                ->where(fn ($q) => $q->whereNull('effective_until')->orWhere('effective_until', '>=', now()))
                                                ->latest('effective_from')
                                                ->value('amount') ?? 0;
                                            $set('price', (float) $tariff);
                                        }
                                    }),

                                TextInput::make('price')
                                    ->label('Harga Snapshot')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->default(0),
                            ])
                            ->columns(2)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $items = $get('parameters') ?? [];
                                $total = collect($items)->sum(fn ($item) => (float) ($item['price'] ?? 0));
                                $set('total_amount', $total);
                            }),

                        TextInput::make('total_amount')
                            ->label('Total Biaya Retribusi')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->default(0),
                    ]),

                Section::make('Informasi Pengambilan Sampel')
                    ->description('Lokasi dan waktu pengambilan sampel di lapangan bila relevan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('sampling_location')
                                ->label('Titik / Lokasi Pengambilan Sampel')
                                ->placeholder('mis. Kran Dapur Warga / Outlet IPAL Faskes')
                                ->maxLength(255)
                                ->nullable(),

                            DatePicker::make('sampling_date')
                                ->label('Tanggal Sampling')
                                ->nullable(),

                            Textarea::make('notes')
                                ->label('Catatan Permohonan')
                                ->rows(2)
                                ->nullable()
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Status & Estimasi Waktu (SLA)')
                    ->description('Monitoring status alur pelayanan dan batas waktu penyelesaian')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('status')
                                ->label('Status Permohonan')
                                ->options([
                                    'draft' => 'Draf Permohonan',
                                    'submitted' => 'Menunggu Verifikasi',
                                    'awaiting_payment' => 'Menunggu Pembayaran',
                                    'awaiting_sample' => 'Menunggu Penerimaan Sampel',
                                    'in_progress' => 'Sedang Pengujian',
                                    'validated' => 'Hasil Tervalidasi',
                                    'report_issued' => 'LHU Terbit',
                                    'completed' => 'Selesai & Diserahkan',
                                    'cancelled' => 'Dibatalkan',
                                ])
                                ->default('submitted')
                                ->required(),

                            Select::make('payment_status')
                                ->label('Status Pembayaran')
                                ->options([
                                    'unpaid' => 'Belum Bayar',
                                    'paid' => 'Lunas',
                                    'refunded' => 'Dikembalikan',
                                ])
                                ->default('unpaid')
                                ->required(),

                            DatePicker::make('estimated_completion_date')
                                ->label('Estimasi Tanggal Selesai (SLA)')
                                ->nullable(),
                        ]),
                    ]),
            ]);
    }
}
