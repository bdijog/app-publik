<?php

namespace App\Filament\Resources\TestRequests\Pages;

use App\Filament\Resources\TestRequests\TestRequestResource;
use App\Services\Export\Excel\TestRequestExcelExporter;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListTestRequests extends ListRecords
{
    protected static string $resource = TestRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_excel')
                ->label(__('Ekspor Excel (.xlsx)'))
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('success')
                ->action(fn () => TestRequestExcelExporter::export($this->getFilteredTableQuery())),
            CreateAction::make(),
        ];
    }
}
