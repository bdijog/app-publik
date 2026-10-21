<?php

namespace App\Filament\Resources\TestJobs\Tables;

use App\Enums\TestJobStatus;
use App\Models\TestJob;
use Filament\Actions\Action;
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
                    ->label(__('SPK Number'))
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('sample.sample_code')
                    ->label(__('Sample Code'))
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sample.sample_name')
                    ->label(__('Sample Name'))
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('laboratoryUnit.name')
                    ->label(__('Laboratory Unit'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('assignedTo.name')
                    ->label(__('Assigned Analyst'))
                    ->placeholder('Belum Ditugaskan')
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('Status'))
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
                    ->label(__('Start Testing'))
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->label(__('Testing Completed'))
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('laboratory_unit_id')
                    ->label(__('Laboratory Unit'))
                    ->relationship('laboratoryUnit', 'name')
                    ->preload(),

                SelectFilter::make('status')
                    ->label(__('SPK Status'))
                    ->options([
                        'pending' => 'Menunggu Pengerjaan',
                        'in_progress' => 'Sedang Dikerjakan',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->recordActions([
                Action::make('printSpk')
                    ->label('Cetak SPK')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (TestJob $record): string => route('jobs.spk.print', $record))
                    ->openUrlInNewTab(),
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
