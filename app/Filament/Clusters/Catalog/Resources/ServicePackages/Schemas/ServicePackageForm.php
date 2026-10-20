<?php

namespace App\Filament\Clusters\Catalog\Resources\ServicePackages\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServicePackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Paket')
                    ->description('Paket pemeriksaan gabungan beberapa parameter uji')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('service_group_id')
                                ->label(__('Service Group'))
                                ->relationship('serviceGroup', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            TextInput::make('name')
                                ->label(__('Examination Package Name'))
                                ->required()
                                ->maxLength(255)
                                ->placeholder('mis. Paket Kimia Air Bersih Standar Permenkes'),

                            Toggle::make('is_active')
                                ->label(__('Active Status'))
                                ->default(true),
                        ]),
                    ]),

                Section::make('Parameter Uji dalam Paket')
                    ->description('Pilih parameter yang tercakup dalam paket pemeriksaan ini')
                    ->schema([
                        CheckboxList::make('parameters')
                            ->label(__('Parameters List'))
                            ->relationship('parameters', 'name')
                            ->columns(3)
                            ->searchable()
                            ->bulkToggleable(),
                    ]),
            ]);
    }
}
