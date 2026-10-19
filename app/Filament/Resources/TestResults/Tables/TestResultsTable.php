<?php

namespace App\Filament\Resources\TestResults\Tables;

use App\Enums\TestResultStatus;
use App\Models\ResultRevision;
use App\Models\ResultValidation;
use App\Models\TestResult;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TestResultsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('testJob.job_number')
                    ->label('Nomor SPK')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('testJob.sample.sample_code')
                    ->label('Kode Sampel')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('testRequestParameter.testParameter.name')
                    ->label('Parameter Uji')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('result_value')
                    ->label('Hasil Pengukuran')
                    ->state(fn (TestResult $r): string => $r->value_numeric !== null
                        ? "{$r->value_numeric} {$r->unit}"
                        : ($r->value_text ?? '-'))
                    ->weight('bold'),

                TextColumn::make('threshold_status')
                    ->label('Status Baku Mutu')
                    ->badge()
                    ->state(function (TestResult $r): string {
                        $param = $r->testRequestParameter?->testParameter;
                        if (! $param) {
                            return '-';
                        }
                        if ($r->value_numeric !== null) {
                            $val = (float) $r->value_numeric;
                            if ($param->threshold_min !== null && $val < (float) $param->threshold_min) {
                                return 'Di Bawah Baku Mutu';
                            }
                            if ($param->threshold_max !== null && $val > (float) $param->threshold_max) {
                                return 'Melebihi Baku Mutu';
                            }
                            if ($param->threshold_min !== null || $param->threshold_max !== null) {
                                return 'Sesuai Baku Mutu';
                            }
                        }

                        return 'Hasil Dicatat';
                    })
                    ->color(fn ($state): string => match ($state) {
                        'Sesuai Baku Mutu' => 'success',
                        'Melebihi Baku Mutu', 'Di Bawah Baku Mutu' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('status')
                    ->label('Status Validasi')
                    ->badge()
                    ->color(fn ($state): string => match ($state?->value ?? $state) {
                        'validated', TestResultStatus::Validated => 'success',
                        'submitted', TestResultStatus::Submitted => 'info',
                        'returned', TestResultStatus::Returned => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(function ($state): string {
                        if ($state instanceof TestResultStatus) {
                            return $state->label();
                        }

                        return match ($state) {
                            'draft' => 'Draf',
                            'submitted' => 'Menunggu Validasi',
                            'returned' => 'Dikembalikan',
                            'validated' => 'Tervalidasi',
                            default => (string) $state,
                        };
                    }),

                IconColumn::make('is_locked')
                    ->label('Terkunci')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('analyst.name')
                    ->label('Analis')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Hasil')
                    ->options([
                        'draft' => 'Draf Hasil',
                        'submitted' => 'Menunggu Validasi',
                        'returned' => 'Dikembalikan',
                        'validated' => 'Tervalidasi',
                    ]),
            ])
            ->recordActions([
                Action::make('submit_validation')
                    ->label('Kirim Validasi')
                    ->icon(Heroicon::OutlinedPaperAirplane)
                    ->color('info')
                    ->visible(fn (TestResult $r) => in_array($r->status?->value ?? $r->status, ['draft', 'returned']))
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Hasil ke Validator')
                    ->modalDescription('Hasil pengujian ini akan dikirimkan ke antrean review Manager Teknis / Lab.')
                    ->action(function (TestResult $record) {
                        $record->status = TestResultStatus::Submitted;
                        $record->save();

                        Notification::make()
                            ->title('Hasil Dikirim ke Validator')
                            ->body('Hasil uji telah diteruskan untuk ditinjau oleh validator.')
                            ->info()
                            ->send();
                    }),

                Action::make('validate_result')
                    ->label('Validasi')
                    ->icon(Heroicon::OutlinedCheckBadge)
                    ->color('success')
                    ->visible(fn (TestResult $r) => ($r->status?->value ?? $r->status) === 'submitted' && auth('employee')->user()?->can('result.validate'))
                    ->modalHeading(fn (TestResult $r) => 'Validasi Hasil Uji: '.($r->testRequestParameter?->testParameter?->name ?? 'Parameter'))
                    ->form([
                        Select::make('stage')
                            ->label('Tahapan Validasi')
                            ->options([
                                'manager_lab' => 'Validasi Manager Teknis / Lab',
                                'recommendation' => 'Validasi Rekomendasi Teknis',
                            ])
                            ->default('manager_lab')
                            ->required(),

                        Select::make('decision')
                            ->label('Keputusan')
                            ->options([
                                'approved' => 'Setujui (Approved)',
                                'returned' => 'Kembalikan untuk Perbaikan (Returned)',
                            ])
                            ->default('approved')
                            ->required()
                            ->live(),

                        Textarea::make('notes')
                            ->label('Catatan Koreksi / Penjelasan')
                            ->required(fn (Get $get) => $get('decision') === 'returned')
                            ->placeholder('Wajib diisi jika hasil dikembalikan kepada analis untuk perbaikan'),
                    ])
                    ->action(function (array $data, TestResult $record) {
                        ResultValidation::create([
                            'test_result_id' => $record->id,
                            'validator_id' => auth('employee')->id(),
                            'stage' => $data['stage'],
                            'decision' => $data['decision'],
                            'notes' => $data['notes'] ?? null,
                            'decided_at' => now(),
                        ]);

                        if ($data['decision'] === 'approved') {
                            $record->status = TestResultStatus::Validated;
                            $record->is_locked = true;
                        } else {
                            $record->status = TestResultStatus::Returned;
                            $record->is_locked = false;
                        }
                        $record->save();

                        Notification::make()
                            ->title('Keputusan Validasi Disimpan')
                            ->body('Keputusan validasi berhasil dicatat.')
                            ->success()
                            ->send();
                    }),

                Action::make('revise')
                    ->label('Koreksi / Revisi')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->color('warning')
                    ->visible(fn (TestResult $r) => $r->is_locked && auth('employee')->user()?->can('result.revise'))
                    ->modalHeading('Revisi Hasil Uji yang Terkunci')
                    ->modalDescription('Setiap perubahan pada hasil yang sudah divalidasi wajib mencantumkan alasan formal untuk jejak audit.')
                    ->form([
                        TextInput::make('new_value_numeric')
                            ->label('Nilai Baru (Angka / Numerik)')
                            ->numeric()
                            ->default(fn (TestResult $r) => $r->value_numeric),

                        TextInput::make('new_value_text')
                            ->label('Nilai Baru (Kualitatif / Teks)')
                            ->default(fn (TestResult $r) => $r->value_text),

                        Textarea::make('reason')
                            ->label('Alasan Perubahan / Koreksi')
                            ->required()
                            ->placeholder('Wajib diisi untuk audit trail laboratorium'),
                    ])
                    ->action(function (array $data, TestResult $record) {
                        $oldVal = $record->value_numeric !== null ? (string) $record->value_numeric : (string) $record->value_text;
                        $newVal = filled($data['new_value_numeric']) ? (string) $data['new_value_numeric'] : (string) ($data['new_value_text'] ?? '');

                        ResultRevision::create([
                            'test_result_id' => $record->id,
                            'revised_by' => auth('employee')->id(),
                            'old_value' => $oldVal,
                            'new_value' => $newVal,
                            'reason' => $data['reason'],
                            'created_at' => now(),
                        ]);

                        if (filled($data['new_value_numeric'])) {
                            $record->value_numeric = $data['new_value_numeric'];
                        }
                        if (filled($data['new_value_text'])) {
                            $record->value_text = $data['new_value_text'];
                        }
                        $record->save();

                        Notification::make()
                            ->title('Revisi Hasil Tercatat')
                            ->body('Perubahan hasil dan jejak audit berhasil disimpan.')
                            ->warning()
                            ->send();
                    }),

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
