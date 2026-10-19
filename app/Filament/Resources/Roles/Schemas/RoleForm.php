<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Peran')
                    ->description('Nama dan cakupan guard peran sistem')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Peran')
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

                Section::make('Hak Akses (Permissions)')
                    ->description('Pilih izin granular yang dimiliki oleh peran ini')
                    ->schema([
                        CheckboxList::make('permissions')
                            ->label('Daftar Izin')
                            ->relationship('permissions', 'name', fn ($query) => $query->where('guard_name', 'employee'))
                            ->columns(3)
                            ->searchable()
                            ->bulkToggleable(),
                    ]),
            ]);
    }
}
