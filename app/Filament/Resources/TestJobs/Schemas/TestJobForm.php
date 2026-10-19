<?php

namespace App\Filament\Resources\TestJobs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class TestJobForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Surat Perintah Kerja (SPK) Laboratorium')
                    ->description('Distribusi penugasan pengujian sampel per instalasi dan analis pelaksana')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('job_number')
                                ->label('Nomor SPK Pengujian')
                                ->placeholder('Dibuat otomatis oleh sistem')
                                ->disabled()
                                ->dehydrated(),

                            Select::make('sample_id')
                                ->label('Sampel Fisik')
                                ->relationship('sample', 'sample_code')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('laboratory_unit_id')
                                ->label('Instalasi Laboratorium Penanggung Jawab')
                                ->relationship('laboratoryUnit', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live(),

                            Select::make('assigned_to')
                                ->label('Analis Pelaksana Ditugaskan')
                                ->relationship('assignedTo', 'name', fn (Builder $query, Get $get) => $query->when(
                                    $get('laboratory_unit_id'),
                                    fn ($q, $unitId) => $q->where('laboratory_unit_id', $unitId)
                                ))
                                ->searchable()
                                ->preload()
                                ->nullable()
                                ->placeholder('Pilih analis laboratorium'),

                            Select::make('status')
                                ->label('Status SPK')
                                ->options([
                                    'pending' => 'Menunggu Pengerjaan',
                                    'in_progress' => 'Sedang Dikerjakan',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ])
                                ->default('pending')
                                ->required(),

                            Grid::make(2)->schema([
                                DateTimePicker::make('started_at')
                                    ->label('Waktu Mulai Pengujian'),

                                DateTimePicker::make('completed_at')
                                    ->label('Waktu Selesai Pengujian'),
                            ])->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
