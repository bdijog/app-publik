<?php

namespace App\Filament\Clusters\Catalog\Resources\LaboratoryUnits\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LaboratoryUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Instalasi')
                    ->description('Data unit/instalasi laboratorium penanggung jawab pengujian')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('code')
                                ->label('Kode Instalasi')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(20)
                                ->placeholder('mis. LAB-KIM, LAB-MIKRO'),

                            TextInput::make('name')
                                ->label('Nama Instalasi / Unit')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('mis. Instalasi Pengujian Kimia'),
                        ]),
                    ]),
            ]);
    }
}
