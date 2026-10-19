<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Izin / Hak Akses')
                    ->description('Definisi permission granular untuk pegawai')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Izin (contoh: request.create)')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),

                            TextInput::make('guard_name')
                                ->label('Guard')
                                ->default('employee')
                                ->disabled()
                                ->dehydrated()
                                ->required(),
                        ]),
                    ]),
            ]);
    }
}
