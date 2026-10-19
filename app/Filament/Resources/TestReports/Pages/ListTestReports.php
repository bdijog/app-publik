<?php

namespace App\Filament\Resources\TestReports\Pages;

use App\Filament\Resources\TestReports\TestReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTestReports extends ListRecords
{
    protected static string $resource = TestReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
