<?php

namespace App\Filament\Clusters\Catalog\Resources\Tariffs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class TariffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Objek Retribusi / Tarif')
                    ->description('Tentukan objek yang dikenakan tarif (parameter, paket, atau kelompok layanan)')
                    ->schema([
                        Select::make('target_type')
                            ->label(__('Tariff Object Level'))
                            ->options([
                                'parameter' => 'Per Parameter Uji',
                                'package' => 'Per Paket Layanan',
                                'group' => 'Per Kelompok Layanan',
                            ])
                            ->default('parameter')
                            ->live()
                            ->afterStateHydrated(function (Set $set, $record) {
                                if ($record) {
                                    if ($record->test_parameter_id) {
                                        $set('target_type', 'parameter');
                                    } elseif ($record->service_package_id) {
                                        $set('target_type', 'package');
                                    } elseif ($record->service_group_id) {
                                        $set('target_type', 'group');
                                    }
                                }
                            })
                            ->afterStateUpdated(function (Set $set) {
                                $set('test_parameter_id', null);
                                $set('service_package_id', null);
                                $set('service_group_id', null);
                            }),

                        Grid::make(1)->schema([
                            Select::make('test_parameter_id')
                                ->label(__('Test Parameter'))
                                ->relationship('testParameter', 'name')
                                ->searchable()
                                ->preload()
                                ->visible(fn (Get $get) => $get('target_type') === 'parameter')
                                ->required(fn (Get $get) => $get('target_type') === 'parameter'),

                            Select::make('service_package_id')
                                ->label(__('Examination Package'))
                                ->relationship('servicePackage', 'name')
                                ->searchable()
                                ->preload()
                                ->visible(fn (Get $get) => $get('target_type') === 'package')
                                ->required(fn (Get $get) => $get('target_type') === 'package'),

                            Select::make('service_group_id')
                                ->label(__('Service Group'))
                                ->relationship('serviceGroup', 'name')
                                ->searchable()
                                ->preload()
                                ->visible(fn (Get $get) => $get('target_type') === 'group')
                                ->required(fn (Get $get) => $get('target_type') === 'group'),
                        ]),
                    ]),

                Section::make('Besaran Tarif & Masa Berlaku')
                    ->description('Nominal tarif retribusi resmi dan rentang waktu berlakunya')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('amount')
                                ->label(__('Tariff Amount'))
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->minValue(0)
                                ->placeholder('mis. 50000'),

                            DatePicker::make('effective_from')
                                ->label(__('Valid From'))
                                ->default(now())
                                ->required(),

                            DatePicker::make('effective_until')
                                ->label(__('Valid Until'))
                                ->nullable()
                                ->helperText('Kosongkan bila masih berlaku saat ini'),
                        ]),
                    ]),
            ]);
    }
}
