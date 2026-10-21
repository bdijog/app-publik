<?php

namespace App\Filament\Widgets\Lab;

use App\Enums\TestResultStatus;
use App\Models\Employee;
use App\Models\TestResult;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class ResultValidationQueueTableWidget extends TableWidget
{
    protected static ?int $sort = 14;

    protected int|string|array $columnSpan = 'half';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && ($user->hasRole('lab_officer') || $user->hasRole('admin'));
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Antrean Hasil Uji Menunggu Validasi Teknis')
            ->query(
                TestResult::query()
                    ->where('status', TestResultStatus::Submitted->value)
                    ->where('is_locked', false)
                    ->with(['testJob.sample', 'testRequestParameter.testParameter', 'analyst'])
                    ->latest('updated_at')
            )
            ->columns([
                TextColumn::make('testJob.job_number')
                    ->label('No. SPK')
                    ->badge()
                    ->searchable(),

                TextColumn::make('testRequestParameter.testParameter.name')
                    ->label('Parameter')
                    ->searchable(),

                TextColumn::make('analyst.name')
                    ->label('Analis')
                    ->limit(18)
                    ->searchable(),

                TextColumn::make('value')
                    ->label('Nilai Hasil')
                    ->state(fn (TestResult $record) => $record->value_numeric !== null ? "{$record->value_numeric} {$record->unit}" : ($record->value_text ?? '-')),

                TextColumn::make('tested_at')
                    ->label('Tgl Uji')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->paginated([5, 10]);
    }
}
