<?php

namespace App\Filament\Resources\TestRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi FPPS & Pemohon')
                    ->description('Detail formulir permintaan pemeriksaan sampel')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('fpps_number')
                                ->label(__('FPPS Number'))
                                ->badge()
                                ->color('primary')
                                ->copyable(),

                            TextEntry::make('customer.name')
                                ->label(__('Applicant Name')),

                            TextEntry::make('customer.organization_name')
                                ->label(__('Institution / Health Facility'))
                                ->placeholder('-'),

                            TextEntry::make('customer.phone')
                                ->label(__('WhatsApp Contact')),

                            TextEntry::make('serviceGroup.name')
                                ->label(__('Service Group'))
                                ->badge()
                                ->color('info'),

                            TextEntry::make('servicePackage.name')
                                ->label(__('Service Package'))
                                ->placeholder('-'),
                        ]),

                        Grid::make(2)->schema([
                            TextEntry::make('purpose')
                                ->label(__('Testing Purpose')),

                            TextEntry::make('channel')
                                ->label(__('Registration Channel'))
                                ->badge(),
                        ]),
                    ]),

                Section::make('Biaya & Status Layanan')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('total_amount')
                                ->label(__('Total Retribution Fee'))
                                ->money('IDR', locale: 'id_ID')
                                ->weight('bold'),

                            TextEntry::make('payment_status')
                                ->label(__('Payment Status'))
                                ->badge(),

                            TextEntry::make('status')
                                ->label(__('Workflow Status'))
                                ->badge(),

                            TextEntry::make('estimated_completion_date')
                                ->label(__('Estimated Completion (SLA)'))
                                ->date('d F Y')
                                ->placeholder('-'),

                            TextEntry::make('registered_at')
                                ->label(__('Registration Timestamp'))
                                ->dateTime('d F Y, H:i'),

                            TextEntry::make('creator.name')
                                ->label(__('Registration Officer'))
                                ->placeholder('Didaftarkan Mandiri (Online)'),
                        ]),
                    ]),

                Section::make('Pengambilan Sampel')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('sampling_location')
                                ->label(__('Sampling Point / Site'))
                                ->placeholder('-'),

                            TextEntry::make('sampling_date')
                                ->label(__('Sampling Date'))
                                ->date('d F Y')
                                ->placeholder('-'),

                            TextEntry::make('notes')
                                ->label(__('Additional Notes'))
                                ->placeholder('-')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
