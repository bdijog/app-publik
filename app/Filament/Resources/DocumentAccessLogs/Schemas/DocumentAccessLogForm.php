<?php

namespace App\Filament\Resources\DocumentAccessLogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentAccessLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Catatan Audit Akses Dokumen LHU')
                    ->description('Rekaman jejak verifikasi QR / akses dokumen oleh publik, pelanggan, atau pegawai internal.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('test_report_id')
                                    ->relationship('testReport', 'report_number')
                                    ->label(__('LHU Number'))
                                    ->disabled(),

                                TextInput::make('action')
                                    ->label(__('Access Activity'))
                                    ->disabled(),

                                Select::make('customer_id')
                                    ->relationship('customer', 'name')
                                    ->label(__('Customer Account'))
                                    ->disabled(),

                                Select::make('employee_id')
                                    ->relationship('employee', 'name')
                                    ->label(__('Accessing Employee'))
                                    ->disabled(),

                                TextInput::make('ip_address')
                                    ->label(__('Accessor IP Address'))
                                    ->disabled(),

                                DateTimePicker::make('accessed_at')
                                    ->label(__('Access Time'))
                                    ->disabled(),
                            ]),
                    ]),
            ]);
    }
}
