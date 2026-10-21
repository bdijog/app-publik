<?php

namespace App\Filament\Resources\Samples\Pages;

use App\Filament\Resources\Samples\SampleResource;
use App\Services\Export\Excel\SampleReceiptExcelExporter;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListSamples extends ListRecords
{
    protected static string $resource = SampleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_excel')
                ->label(__('Ekspor Data Sampel (.xlsx)'))
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('success')
                ->action(fn () => SampleReceiptExcelExporter::export()),
            CreateAction::make(),
        ];
    }
}
