<?php

namespace App\Filament\Resources\Permissions\Schemas;

use App\Enums\PermissionEnum;
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
                    ->description('Definisi permission terpadu (CRUD) untuk pegawai (guard employee)')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label(__('Permission Code (e.g. request.manage)'))
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->datalist(PermissionEnum::values())
                                ->helperText('Pilih dari daftar standar PermissionEnum atau masukkan kode berformat domain.manage'),

                            TextInput::make('guard_name')
                                ->label(__('Guard'))
                                ->default('employee')
                                ->disabled()
                                ->dehydrated()
                                ->required(),
                        ]),
                    ]),
            ]);
    }
}
