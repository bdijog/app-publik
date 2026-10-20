<?php

namespace App\Filament\Clusters\Catalog\Resources\ServiceGroups\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kelompok Layanan')
                    ->description('Kelompok layanan pemeriksaan laboratorium sesuai brosur (Air Laut, Makanan, Vektor, dll.)')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('laboratory_unit_id')
                                ->label(__('Responsible Unit'))
                                ->relationship('laboratoryUnit', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            TextInput::make('code')
                                ->label(__('Service Group Code'))
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(20)
                                ->placeholder('mis. AIR-LAUT, MAKANAN'),

                            TextInput::make('name')
                                ->label(__('Service Group Name'))
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull()
                                ->placeholder('mis. Pengujian Kualitas Air Laut'),

                            Textarea::make('description')
                                ->label(__('Service Description'))
                                ->rows(3)
                                ->nullable()
                                ->columnSpanFull(),

                            Toggle::make('is_active')
                                ->label(__('Active Status'))
                                ->default(true),
                        ]),
                    ]),
            ]);
    }
}
