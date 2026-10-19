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
                                    ->label('Parameter Pengujian')
                                    ->disabled(),

                                Select::make('revised_by')
                                    ->relationship('revisedBy', 'name')
                                    ->label('Direvisi Oleh')
                                    ->disabled(),

                                TextInput::make('old_value')
                                    ->label('Nilai Lama (Sebelum Revisi)')
                                    ->disabled(),

                                TextInput::make('new_value')
                                    ->label('Nilai Baru (Setelah Revisi)')
                                    ->disabled(),

                                Textarea::make('reason')
                                    ->label('Alasan / Justifikasi Revisi')
                                    ->columnSpanFull()
                                    ->disabled(),

                                DateTimePicker::make('created_at')
                                    ->label('Waktu Pencatatan Revisi')
                                    ->disabled(),
                            ]),
                    ]),
            ]);
    }
}
