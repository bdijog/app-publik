<?php

namespace App\Filament\Clusters\Catalog\Resources\TestParameters\Schemas;

use App\Enums\ResultType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class TestParameterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Parameter')
                    ->description('Definisi parameter uji, metode baku, dan satuan pengukuran')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('service_group_id')
                                ->label(__('Service Group'))
                                ->relationship('serviceGroup', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            TextInput::make('name')
                                ->label(__('Test Parameter Name'))
                                ->required()
                                ->maxLength(255)
                                ->placeholder('mis. pH, Kekeruhan, Timbal (Pb), Angka Kuman'),

                            TextInput::make('unit')
                                ->label(__('Standard Unit'))
                                ->maxLength(30)
                                ->placeholder('mis. mg/L, CFU/mL, NTU, °C')
                                ->nullable(),

                            TextInput::make('method')
                                ->label(__('Standard Analysis Method'))
                                ->maxLength(255)
                                ->placeholder('mis. SNI 06-6989.11-2004, Spektrofotometri')
                                ->nullable(),

                            Select::make('result_type')
                                ->label(__('Result Type'))
                                ->options([
                                    'numeric' => 'Angka / Numerik',
                                    'text' => 'Teks / Kualitatif',
                                ])
                                ->required()
                                ->live(),

                            Toggle::make('is_active')
                                ->label(__('Active Status'))
                                ->default(true),
                        ]),
                    ]),

                Section::make('Standar Baku Mutu / Nilai Rujukan')
                    ->description('Nilai ambang batas untuk evaluasi kesesuaian hasil uji')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('threshold_min')
                                ->label(__('Minimum Limit (Lower Limit)'))
                                ->numeric()
                                ->visible(fn (Get $get) => in_array($get('result_type'), ['numeric', ResultType::Numeric->value, ResultType::Numeric])),

                            TextInput::make('threshold_max')
                                ->label(__('Maximum Limit (Upper Limit)'))
                                ->numeric()
                                ->visible(fn (Get $get) => in_array($get('result_type'), ['numeric', ResultType::Numeric->value, ResultType::Numeric])),

                            TextInput::make('threshold_text')
                                ->label(__('Qualitative Quality Standard Description (Text)'))
                                ->placeholder('mis. Negatif / 100 mL, Tidak Berbau, Jernih')
                                ->maxLength(255)
                                ->columnSpanFull()
                                ->visible(fn (Get $get) => in_array($get('result_type'), ['text', ResultType::Text->value, ResultType::Text])),
                        ]),
                    ]),
            ]);
    }
}
