<?php

namespace App\Filament\Clusters\Catalog\Resources\SampleTypes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SampleTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Jenis Sampel')
                    ->description('Kategori sampel beserta persyaratan fisik wadah dan volume minimum')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('service_group_id')
                                ->label(__('Service Group'))
                                ->relationship('serviceGroup', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            TextInput::make('name')
                                ->label(__('Sample Type Name'))
                                ->required()
                                ->maxLength(255)
                                ->placeholder('mis. Air Sumur Gali, Makanan Siap Saji, Usap Alat'),

                            TextInput::make('min_volume')
                                ->label(__('Minimum Volume / Weight'))
                                ->numeric()
                                ->placeholder('mis. 500')
                                ->helperText('Volume minimum yang harus dipenuhi saat penerimaan sampel fisik'),

                            TextInput::make('container_requirement')
                                ->label(__('Container Requirements'))
                                ->maxLength(255)
                                ->placeholder('mis. Botol Gelap Steril, Coolbox 4°C, Plastik Ziplock'),

                            Textarea::make('requirements')
                                ->label(__('Special Instructions / Other Requirements'))
                                ->rows(3)
                                ->columnSpanFull()
                                ->placeholder('mis. Wajib disimpan pada suhu 2-8°C, diantar maksimal 24 jam pasca sampling'),
                        ]),
                    ]),
            ]);
    }
}
