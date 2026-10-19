<?php

namespace App\Filament\Resources\ActivityLogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ActivityLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rekaman Jejak Aktivitas Sistem')
                    ->description('Detail entitas pelaksana (causer), subjek terdampak, dan metadata perubahan.')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('action')
                                    ->label('Nama Aksi / Event')
                                    ->disabled(),

                                TextInput::make('causer_type')
                                    ->label('Tipe Pelaksana')
                                    ->formatStateUsing(fn ($state) => class_basename($state))
                                    ->disabled(),

                                TextInput::make('causer_id')
                                    ->label('ID Pelaksana')
                                    ->disabled(),

                                TextInput::make('subject_type')
                                    ->label('Tipe Subjek')
                                    ->formatStateUsing(fn ($state) => class_basename($state))
                                    ->disabled(),

                                TextInput::make('subject_id')
                                    ->label('ID Subjek')
                                    ->disabled(),

                                DateTimePicker::make('created_at')
                                    ->label('Waktu Kejadian')
                                    ->disabled(),
                            ]),

                        KeyValue::make('properties')
                            ->label('Metadata & Parameter Properti (JSON)')
                            ->columnSpanFull()
                            ->disabled(),
                    ]),
            ]);
    }
}
