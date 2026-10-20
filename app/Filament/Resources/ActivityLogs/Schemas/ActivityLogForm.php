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
                                    ->label(__('Action / Event Name'))
                                    ->disabled(),

                                TextInput::make('causer_type')
                                    ->label(__('Performer Type'))
                                    ->formatStateUsing(fn ($state) => class_basename($state))
                                    ->disabled(),

                                TextInput::make('causer_id')
                                    ->label(__('Performer ID'))
                                    ->disabled(),

                                TextInput::make('subject_type')
                                    ->label(__('Subject Type'))
                                    ->formatStateUsing(fn ($state) => class_basename($state))
                                    ->disabled(),

                                TextInput::make('subject_id')
                                    ->label(__('Subject ID'))
                                    ->disabled(),

                                DateTimePicker::make('created_at')
                                    ->label(__('Event Time'))
                                    ->disabled(),
                            ]),

                        KeyValue::make('properties')
                            ->label(__('Metadata & Property Parameters (JSON)'))
                            ->columnSpanFull()
                            ->disabled(),
                    ]),
            ]);
    }
}
