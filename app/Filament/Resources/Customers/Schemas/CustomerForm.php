<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Pemohon / Pelanggan')
                    ->description('Data pelanggan masyarakat, fasilitas kesehatan, atau instansi pemohon layanan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap Pemohon / PIC')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('mis. dr. Hendra Saputra / Budi Santoso'),

                            TextInput::make('organization_name')
                                ->label('Nama Instansi / Perusahaan / Faskes')
                                ->nullable()
                                ->maxLength(255)
                                ->placeholder('mis. RSUD Dr. M. Haulussy / PT Tirta Maluku'),

                            Select::make('customer_type')
                                ->label('Kategori Pemohon')
                                ->options([
                                    'individual' => 'Perorangan',
                                    'organization' => 'Instansi / Perusahaan',
                                    'health_facility' => 'Fasilitas Kesehatan',
                                ])
                                ->default('individual')
                                ->required(),

                            TextInput::make('phone')
                                ->label('Nomor Telepon / WhatsApp')
                                ->tel()
                                ->required()
                                ->placeholder('mis. 08123456789')
                                ->helperText('Nomor ini digunakan pelanggan untuk pencocokan verifikasi unduh LHU'),

                            TextInput::make('email')
                                ->label('Email (Opsional)')
                                ->email()
                                ->nullable()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            Textarea::make('address')
                                ->label('Alamat Lengkap')
                                ->rows(3)
                                ->nullable()
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
