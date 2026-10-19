<?php

namespace App\Filament\Resources\TestRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi FPPS & Pemohon')
                    ->description('Detail formulir permintaan pemeriksaan sampel')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('fpps_number')
                                ->label('Nomor FPPS')
                                ->badge()
                                ->color('primary')
                                ->copyable(),

                            TextEntry::make('customer.name')
                                ->label('Nama Pemohon'),

                            TextEntry::make('customer.organization_name')
                                ->label('Instansi / Faskes')
                                ->placeholder('-'),

                            TextEntry::make('customer.phone')
                                ->label('Kontak WhatsApp'),

                            TextEntry::make('serviceGroup.name')
                                ->label('Kelompok Layanan')
                                ->badge()
                                ->color('info'),

                            TextEntry::make('servicePackage.name')
                                ->label('Paket Layanan')
                                ->placeholder('-'),
                        ]),

                        Grid::make(2)->schema([
                            TextEntry::make('purpose')
                                ->label('Tujuan Pengujian'),

                            TextEntry::make('channel')
                                ->label('Kanal Pendaftaran')
                                ->badge(),
                        ]),
                    ]),

                Section::make('Biaya & Status Layanan')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('total_amount')
                                ->label('Total Biaya Retribusi')
                                ->money('IDR', locale: 'id_ID')
                                ->weight('bold'),

                            TextEntry::make('payment_status')
                                ->label('Status Pembayaran')
                                ->badge(),

                            TextEntry::make('status')
                                ->label('Status Alur')
                                ->badge(),

                            TextEntry::make('estimated_completion_date')
                                ->label('Estimasi Selesai (SLA)')
                                ->date('d F Y')
                                ->placeholder('-'),

                            TextEntry::make('registered_at')
                                ->label('Waktu Registrasi')
                                ->dateTime('d F Y, H:i'),

                            TextEntry::make('creator.name')
                                ->label('Petugas Pendaftar')
                                ->placeholder('Didaftarkan Mandiri (Online)'),
                        ]),
                    ]),

                Section::make('Pengambilan Sampel')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('sampling_location')
                                ->label('Titik / Lokasi Sampling')
                                ->placeholder('-'),

                            TextEntry::make('sampling_date')
                                ->label('Tanggal Sampling')
                                ->date('d F Y')
                                ->placeholder('-'),

                            TextEntry::make('notes')
                                ->label('Catatan Tambahan')
                                ->placeholder('-')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
