<?php

namespace App\Filament\Widgets\Lab;

use App\Enums\TestResultStatus;
use App\Models\Employee;
use App\Models\TestResult;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class ReturnedResultsAlertTableWidget extends TableWidget
{
    protected static ?int $sort = 15;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && ($user->hasRole('lab_officer') || $user->hasRole('admin'));
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Peringatan: Hasil Uji Dikembalikan (Perlu Koreksi & Revisi Analis)')
            ->query(
                TestResult::query()
                    ->where('status', TestResultStatus::Returned->value)
                    ->with(['testJob.sample', 'testRequestParameter.testParameter', 'analyst', 'resultValidations.validator'])
                    ->latest('updated_at')
            )
            ->columns([
                TextColumn::make('testJob.job_number')
                    ->label('No. SPK')
                    ->badge()
                    ->color('danger')
                    ->searchable(),

                TextColumn::make('testRequestParameter.testParameter.name')
                    ->label('Parameter Uji')
                    ->searchable(),

                TextColumn::make('analyst.name')
                    ->label('Analis')
                    ->searchable(),

                TextColumn::make('latest_correction_notes')
                    ->label('Catatan Koreksi Validator')
                    ->state(function (TestResult $record): string {
                        $latestValidation = $record->resultValidations()->latest('decided_at')->first();

                        return $latestValidation?->notes ?? 'Perlu koreksi teknis';
                    })
                    ->wrap()
                    ->limit(50),

                TextColumn::make('updated_at')
                    ->label('Waktu Dikembalikan')
                    ->since()
                    ->sortable(),
            ])
            ->paginated([5, 10]);
    }
}
