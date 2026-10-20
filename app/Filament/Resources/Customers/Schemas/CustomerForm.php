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
                                ->label(__('Applicant / PIC Full Name'))
                                ->required()
                                ->maxLength(255)
                                ->placeholder('mis. dr. Hendra Saputra / Budi Santoso'),

                            TextInput::make('organization_name')
                                ->label(__('Institution / Company / Health Facility Name'))
                                ->nullable()
                                ->maxLength(255)
                                ->placeholder('mis. RSUD Dr. M. Haulussy / PT Tirta Maluku'),

                            Select::make('customer_type')
                                ->label(__('Applicant Category'))
                                ->options([
                                    'individual' => 'Perorangan',
                                    'organization' => 'Instansi / Perusahaan',
                                    'health_facility' => 'Fasilitas Kesehatan',
                                ])
                                ->default('individual')
                                ->required(),

                            TextInput::make('phone')
                                ->label(__('Phone / WhatsApp Number'))
                                ->tel()
                                ->required()
                                ->placeholder('mis. 08123456789')
                                ->helperText('Nomor ini digunakan pelanggan untuk pencocokan verifikasi unduh LHU'),

                            TextInput::make('email')
                                ->label(__('Email (Optional)'))
                                ->email()
                                ->nullable()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            Textarea::make('address')
                                ->label(__('Full Address'))
                                ->rows(3)
                                ->nullable()
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
