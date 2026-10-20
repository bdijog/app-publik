<?php

namespace App\Filament\Resources\ReportTemplates\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReportTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Format Template LHU')
                    ->description('Format baku kop surat, klausul akreditasi, dan disclaimer laporan hasil uji laboratorium.')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('Template Name'))
                                    ->placeholder('Contoh: Template LHU Air Minum & Sanitasi')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(2),

                                TextInput::make('version')
                                    ->label(__('Document Version'))
                                    ->placeholder('Contoh: 1.0')
                                    ->required()
                                    ->maxLength(50),
                            ]),

                        Toggle::make('is_active')
                            ->label(__('Active Template'))
                            ->helperText('Hanya template aktif yang dapat digunakan pada penerbitan LHU baru.')
                            ->default(true),

                        Textarea::make('content')
                            ->label(__('Template Structure & Legal Clauses'))
                            ->placeholder('Masukkan klausul akreditasi KAN, ketetapan hukum, atau disclaimer teknis...')
                            ->rows(8)
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
