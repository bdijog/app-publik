<?php

namespace App\Filament\Resources\Payments\Pages;

use App\Filament\Resources\Payments\PaymentResource;
use App\Services\Export\Excel\PaymentExcelExporter;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_excel')
                ->label(__('Ekspor Buku Kas (.xlsx)'))
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('success')
                ->action(fn () => PaymentExcelExporter::export($this->getFilteredTableQuery())),
            CreateAction::make(),
        ];
    }
}
