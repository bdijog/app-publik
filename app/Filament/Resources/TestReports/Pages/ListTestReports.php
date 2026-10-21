<?php

namespace App\Filament\Resources\TestReports\Pages;

use App\Filament\Resources\TestReports\TestReportResource;
use App\Services\Export\Excel\TestReportExcelExporter;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListTestReports extends ListRecords
{
    protected static string $resource = TestReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_excel')
                ->label(__('Ekspor Rekap LHU (.xlsx)'))
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('success')
                ->action(fn () => TestReportExcelExporter::export($this->getFilteredTableQuery())),
            CreateAction::make(),
        ];
    }
}
