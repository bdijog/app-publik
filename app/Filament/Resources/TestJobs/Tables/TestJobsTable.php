<?php

namespace App\Filament\Resources\TestJobs\Tables;

use App\Enums\TestJobStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TestJobsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('job_number')
                    ->label('Nomor SPK')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('sample.sample_code')
                    ->label('Kode Sampel')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sample.sample_name')
                    ->label('Nama Sampel')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('laboratoryUnit.name')
                    ->label('Instalasi')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('assignedTo.name')
                    ->label('Analis Pelaksana')
                    ->placeholder('Belum Ditugaskan')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state): string => match ($state?->value ?? $state) {
                        'completed', TestJobStatus::Completed => 'success',
                        'in_progress', TestJobStatus::InProgress => 'warning',
                        'cancelled', TestJobStatus::Cancelled => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(function ($state): string {
                        if ($state instanceof TestJobStatus) {
                            return $state->label();
                        }

                        return match ($state) {
                            'pending' => 'Menunggu',
                            'in_progress' => 'Sedang Dikerjakan',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                            default => (string) $state,
                        };
                    }),

                TextColumn::make('started_at')
                    ->label('Mulai Uji')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->label('Selesai Uji')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('laboratory_unit_id')
                    ->label('Instalasi')
                    ->relationship('laboratoryUnit', 'name')
                    ->preload(),

                SelectFilter::make('status')
                    ->label('Status SPK')
                    ->options([
                        'pending' => 'Menunggu Pengerjaan',
                        'in_progress' => 'Sedang Dikerjakan',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
