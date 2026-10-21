<?php

namespace App\Filament\Resources\Samples\Tables;

use App\Enums\SampleStatus;
use App\Models\CompletionEstimate;
use App\Models\Sample;
use App\Models\SampleReceipt;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SamplesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sample_code')
                    ->label(__('Sample Code'))
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('sample_name')
                    ->label(__('Sample Name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('testRequest.fpps_number')
                    ->label(__('FPPS Number'))
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sampleType.name')
                    ->label(__('Sample Type'))
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('volume')
                    ->label(__('Volume / Quantity'))
                    ->state(fn (Sample $record): string => "{$record->quantity} {$record->unit}")
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('Acceptance Status'))
                    ->badge()
                    ->color(fn ($state): string => match ($state?->value ?? $state) {
                        'accepted', SampleStatus::Accepted => 'success',
                        'rejected', SampleStatus::Rejected => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(function ($state): string {
                        if ($state instanceof SampleStatus) {
                            return $state->label();
                        }

                        return match ($state) {
                            'accepted' => 'Diterima',
                            'rejected' => 'Ditolak',
                            default => 'Menunggu Penerimaan',
                        };
                    }),

                TextColumn::make('collected_at')
                    ->label(__('Sampling Time'))
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('sample_type_id')
                    ->label(__('Sample Type'))
                    ->relationship('sampleType', 'name')
                    ->preload(),

                SelectFilter::make('status')
                    ->label(__('Acceptance Status'))
                    ->options([
                        'pending' => 'Menunggu Penerimaan',
                        'accepted' => 'Diterima',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                Action::make('verify_receipt')
                    ->label(__('Physical Verification'))
                    ->icon(Heroicon::OutlinedClipboardDocumentCheck)
                    ->color('success')
                    ->modalHeading(fn (Sample $record) => "Verifikasi Fisik Sampel: {$record->sample_code}")
                    ->modalDescription('Periksa kelayakan kondisi fisik wadah, volume, suhu, dan kelengkapan label sampel')
                    ->form([
                        TextInput::make('received_volume')
                            ->label(__('Actual Received Volume / Weight'))
                            ->numeric()
                            ->required()
                            ->default(fn (Sample $record) => $record->quantity)
                            ->helperText('Cocokkan dengan persyaratan volume minimum jenis sampel'),

                        TextInput::make('condition')
                            ->label(__('Sample Physical Condition'))
                            ->required()
                            ->placeholder('mis. Suhu 4°C, Wadah Utuh, Label Terbaca Jelas')
                            ->default('Wadah utuh, suhu terjaga, label terbaca jelas'),

                        Select::make('decision')
                            ->label(__('Acceptance Decision'))
                            ->options([
                                'accepted' => 'Diterima (Layak Uji)',
                                'rejected' => 'Ditolak (Tidak Layak Uji)',
                            ])
                            ->default('accepted')
                            ->required()
                            ->live(),

                        Textarea::make('rejection_reason')
                            ->label(__('Rejection Reason'))
                            ->required(fn (Get $get) => $get('decision') === 'rejected')
                            ->visible(fn (Get $get) => $get('decision') === 'rejected')
                            ->placeholder('mis. Volume tidak memenuhi batas minimum / wadah pecah / suhu melewati batas'),

                        Textarea::make('follow_up')
                            ->label(__('Follow-up Recommendations'))
                            ->required(fn (Get $get) => $get('decision') === 'rejected')
                            ->visible(fn (Get $get) => $get('decision') === 'rejected')
                            ->placeholder('mis. Meminta pemohon mengirimkan sampel ulang dengan wadah steril'),
                    ])
                    ->action(function (array $data, Sample $record) {
                        SampleReceipt::create([
                            'sample_id' => $record->id,
                            'received_by' => auth('employee')->id(),
                            'received_at' => now(),
                            'received_volume' => $data['received_volume'],
                            'condition' => $data['condition'],
                            'decision' => $data['decision'],
                            'rejection_reason' => $data['rejection_reason'] ?? null,
                            'follow_up' => $data['follow_up'] ?? null,
                        ]);

                        $record->status = $data['decision'] === 'accepted' ? SampleStatus::Accepted : SampleStatus::Rejected;
                        $record->save();

                        // Perbarui status TestRequest jika relevan
                        $testRequest = $record->testRequest;
                        if ($testRequest && $data['decision'] === 'accepted') {
                            $estimate = CompletionEstimate::where(function ($q) use ($testRequest) {
                                $q->where('service_group_id', $testRequest->service_group_id)
                                    ->orWhereNull('service_group_id');
                            })
                                ->where('start_point', 'receipt')
                                ->latest('effective_from')
                                ->first();

                            if ($estimate && empty($testRequest->estimated_completion_date)) {
                                $testRequest->estimated_completion_date = now()->addDays((int) $estimate->days);
                                $testRequest->save();
                            }
                        }

                        Notification::make()
                            ->title('Verifikasi Penerimaan Tersimpan')
                            ->body("Sampel {$record->sample_code} berhasil dicatat sebagai {$record->status->label()}.")
                            ->success()
                            ->send();
                    }),

                Action::make('print_rejection_bap')
                    ->label(__('Cetak BAP Tolak'))
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->color('danger')
                    ->visible(fn (Sample $record): bool => ($record->status === SampleStatus::Rejected || $record->status?->value === 'rejected') && $record->latestReceipt !== null)
                    ->url(fn (Sample $record): string => route('samples.receipt.bap', $record->latestReceipt))
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
