<?php

namespace App\Filament\Resources\TestReports\Tables;

use App\Enums\ReportStatus;
use App\Models\ReportHandover;
use App\Models\TestReport;
use App\Services\Export\Pdf\TestReportPdfGenerator;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TestReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('report_number')
                    ->label(__('LHU Number'))
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('testRequest.request_number')
                    ->label(__('FPPS No.'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('testRequest.customer.name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                TextColumn::make('version')
                    ->label(__('Ver'))
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($state) => "v{$state}"),

                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (ReportStatus|string|null $state): string => match ($state instanceof ReportStatus ? $state->value : $state) {
                        'draft' => 'gray',
                        'final' => 'success',
                        'revised' => 'warning',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (ReportStatus|string|null $state): string => match ($state instanceof ReportStatus ? $state->value : $state) {
                        'draft' => 'Draf LHU',
                        'final' => 'Final / Diterbitkan',
                        'revised' => 'Telah Direvisi',
                        default => (string) $state,
                    }),

                TextColumn::make('issued_at')
                    ->label(__('Issuance Time'))
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('handovers_count')
                    ->counts('handovers')
                    ->label(__('Handover'))
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'gray'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('LHU Status'))
                    ->options([
                        ReportStatus::Draft->value => 'Draf LHU',
                        ReportStatus::Final->value => 'Final / Diterbitkan',
                        ReportStatus::Revised->value => 'Telah Direvisi',
                    ]),
            ])
            ->recordActions([
                Action::make('issue_report')
                    ->label(__('Issue LHU'))
                    ->icon(Heroicon::OutlinedCheckBadge)
                    ->color('success')
                    ->visible(fn (TestReport $record) => $record->status === ReportStatus::Draft)
                    ->schema([
                        Textarea::make('considerations')
                            ->label(__('LHU Considerations & Conclusions'))
                            ->default(fn (TestReport $record) => $record->considerations)
                            ->required(),
                        DatePicker::make('validated_on')
                            ->label(__('Technical Validation Date'))
                            ->default(now()->format('Y-m-d'))
                            ->required(),
                    ])
                    ->action(function (TestReport $record, array $data): void {
                        $record->update([
                            'status' => ReportStatus::Final,
                            'considerations' => $data['considerations'],
                            'validated_on' => $data['validated_on'],
                            'issued_by' => auth()->id(),
                            'issued_at' => now(),
                        ]);

                        TestReportPdfGenerator::generateAndSave($record);

                        if ($record->testRequest) {
                            $record->testRequest->update(['status' => 'completed']);
                        }

                        Notification::make()
                            ->title('LHU Berhasil Diterbitkan')
                            ->body("Laporan Hasil Uji {$record->report_number} resmi berstatus Final dan terkunci.")
                            ->success()
                            ->send();
                    }),

                Action::make('revise_report')
                    ->label(__('LHU Revision'))
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->color('warning')
                    ->visible(fn (TestReport $record) => $record->status === ReportStatus::Final)
                    ->schema([
                        Textarea::make('revision_reason')
                            ->label(__('LHU Revision Reason'))
                            ->placeholder('Jelaskan ralat/koreksi nilai hasil pengujian atau data sampel...')
                            ->required(),
                    ])
                    ->action(function (TestReport $record, array $data): void {
                        $newVersion = ((int) $record->version) + 1;

                        TestReport::create([
                            'test_request_id' => $record->test_request_id,
                            'report_template_id' => $record->report_template_id,
                            'parent_report_id' => $record->id,
                            'verifier_id' => $record->verifier_id,
                            'validator_id' => $record->validator_id,
                            'version' => $newVersion,
                            'status' => ReportStatus::Draft,
                            'considerations' => ($record->considerations ?? '')."\n[Revisi v{$newVersion}: {$data['revision_reason']}]",
                        ]);

                        $record->update(['status' => ReportStatus::Revised]);

                        Notification::make()
                            ->title('Draf Revisi Terbentuk')
                            ->body("LHU induk {$record->report_number} berstatus Direvisi, dan draf versi {$newVersion} telah dibuat.")
                            ->warning()
                            ->send();
                    }),

                Action::make('handover')
                    ->label(__('Hand Over LHU'))
                    ->icon(Heroicon::OutlinedTruck)
                    ->color('info')
                    ->visible(fn (TestReport $record) => $record->status === ReportStatus::Final)
                    ->schema([
                        TextInput::make('received_by_name')
                            ->label(__('Recipient / Authorized Pickup Name'))
                            ->required(),
                        DateTimePicker::make('handed_at')
                            ->label(__('Handover Time'))
                            ->default(now())
                            ->required(),
                        Textarea::make('notes')
                            ->label(__('Handover Notes / Expedition Tracking No.'))
                            ->placeholder('Contoh: Diambil langsung oleh ybs / Dikirim via JNE No. Resi...'),
                    ])
                    ->action(function (TestReport $record, array $data): void {
                        ReportHandover::create([
                            'test_report_id' => $record->id,
                            'handed_by' => auth()->id(),
                            'received_by_name' => $data['received_by_name'],
                            'handed_at' => $data['handed_at'],
                            'notes' => $data['notes'] ?? null,
                        ]);

                        Notification::make()
                            ->title('Penyerahan Berhasil Dicatat')
                            ->body("Penyerahan LHU {$record->report_number} kepada {$data['received_by_name']} berhasil dibukukan.")
                            ->success()
                            ->send();
                    }),

                Action::make('download_pdf')
                    ->label(__('Unduh LHU PDF'))
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->color('success')
                    ->visible(fn (TestReport $record): bool => $record->status === ReportStatus::Final)
                    ->action(function (TestReport $record) {
                        return TestReportPdfGenerator::download(
                            $record,
                            employee: auth('employee')->user()
                        );
                    }),

                Action::make('print_pdf')
                    ->label(__('Cetak LHU'))
                    ->icon(Heroicon::OutlinedPrinter)
                    ->color('info')
                    ->visible(fn (TestReport $record): bool => $record->status === ReportStatus::Final)
                    ->url(fn (TestReport $record): string => route('reports.lhu.print', $record))
                    ->openUrlInNewTab(),

                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }
}
