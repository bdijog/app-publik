<?php

namespace App\Filament\Resources\ResultRevisions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ResultRevisionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Audit Jejak Perubahan / Revisi Hasil')
                    ->description('Catatan rekaman historis perubahan data hasil pengujian yang telah dikunci/divalidasi.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('test_result_id')
                                    ->relationship('testResult.testParameter', 'name')
                                    ->label(__('Testing Parameter'))
                                    ->disabled(),

                                Select::make('revised_by')
                                    ->relationship('revisedBy', 'name')
                                    ->label(__('Revised By'))
                                    ->disabled(),

                                TextInput::make('old_value')
                                    ->label(__('Old Value (Before Revision)'))
                                    ->disabled(),

                                TextInput::make('new_value')
                                    ->label(__('New Value (After Revision)'))
                                    ->disabled(),

                                Textarea::make('reason')
                                    ->label(__('Revision Reason / Justification'))
                                    ->columnSpanFull()
                                    ->disabled(),

                                DateTimePicker::make('created_at')
                                    ->label(__('Revision Record Time'))
                                    ->disabled(),
                            ]),
                    ]),
            ]);
    }
}
