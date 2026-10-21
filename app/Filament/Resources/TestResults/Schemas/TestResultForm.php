<?php

namespace App\Filament\Resources\TestResults\Schemas;

use App\Models\TestJob;
use App\Models\TestRequestParameter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class TestResultForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pekerjaan Uji')
                    ->description('Kaitan hasil uji dengan SPK dan parameter yang diuji')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('test_job_id')
                                ->label(__('Test SPK Number'))
                                ->relationship('testJob', 'job_number')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function ($state, Set $set) {
                                    if ($state) {
                                        $job = TestJob::find($state);
                                        if ($job && $job->assigned_to) {
                                            $set('analyst_id', $job->assigned_to);
                                        }
                                    }
                                })
                                ->required(),

                            Select::make('test_request_parameter_id')
                                ->label(__('Testing Parameter'))
                                ->options(function (Get $get) {
                                    $jobId = $get('test_job_id');
                                    if (! $jobId) {
                                        return TestRequestParameter::with('testParameter')
                                            ->get()
                                            ->mapWithKeys(fn ($p) => [$p->id => $p->testParameter?->name ?? "Parameter #{$p->id}"])
                                            ->toArray();
                                    }

                                    $job = TestJob::with('sample.testRequest.parameters.testParameter')->find($jobId);
                                    if (! $job?->sample?->testRequest) {
                                        return [];
                                    }

                                    return $job->sample->testRequest->parameters->mapWithKeys(function ($trp) {
                                        return [$trp->id => $trp->testParameter?->name ?? "Parameter #{$trp->id}"];
                                    })->toArray();
                                })
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function ($state, Set $set) {
                                    if ($state) {
                                        $trp = TestRequestParameter::with('testParameter')->find($state);
                                        if ($trp && $trp->testParameter) {
                                            if ($trp->testParameter->unit) {
                                                $set('unit', $trp->testParameter->unit);
                                            }
                                            if ($trp->testParameter->method) {
                                                $set('method', $trp->testParameter->method);
                                            }
                                        }
                                    }
                                })
                                ->required(),

                            Select::make('analyst_id')
                                ->label(__('Assigned Analyst'))
                                ->relationship('analyst', 'name')
                                ->searchable()
                                ->preload()
                                ->default(fn () => auth('employee')->id())
                                ->required(),
                        ]),
                    ]),

                Section::make('Nilai Pengukuran Laboratorium')
                    ->description('Input angka atau teks hasil pengukuran sesuai baku mutu parameter')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('value_numeric')
                                ->label(__('Result Value (Numeric / Number)'))
                                ->numeric()
                                ->placeholder('mis. 7.45'),

                            TextInput::make('value_text')
                                ->label(__('Result Value (Qualitative / Text)'))
                                ->placeholder('mis. Negatif / Tidak Berbau / Jernih')
                                ->maxLength(255),

                            TextInput::make('unit')
                                ->label(__('Measurement Unit'))
                                ->maxLength(30)
                                ->placeholder('mis. mg/L, CFU/mL, NTU'),

                            TextInput::make('method')
                                ->label(__('Method Used'))
                                ->maxLength(255)
                                ->placeholder('mis. Spektrofotometri / Titrimetri / SNI'),

                            DatePicker::make('tested_at')
                                ->label(__('Testing Date'))
                                ->default(now())
                                ->required(),

                            Select::make('status')
                                ->label(__('Result Status'))
                                ->options([
                                    'draft' => 'Draf Hasil',
                                    'submitted' => 'Menunggu Validasi',
                                    'returned' => 'Dikembalikan untuk Perbaikan',
                                    'validated' => 'Tervalidasi',
                                ])
                                ->default('draft')
                                ->required(),

                            Textarea::make('notes')
                                ->label(__('Analyst Notes / Blank / QC'))
                                ->rows(2)
                                ->columnSpanFull(),

                            Toggle::make('is_locked')
                                ->label(__('Locked Status'))
                                ->helperText('Hasil terkunci tidak dapat diubah kecuali melalui mekanisme revisi resmi')
                                ->disabled()
                                ->dehydrated(),
                        ]),
                    ]),
            ]);
    }
}
