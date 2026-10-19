<?php

namespace App\Filament\Resources\ReportHandovers\Pages;

use App\Filament\Resources\ReportHandovers\ReportHandoverResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReportHandovers extends ListRecords
{
    protected static string $resource = ReportHandoverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
