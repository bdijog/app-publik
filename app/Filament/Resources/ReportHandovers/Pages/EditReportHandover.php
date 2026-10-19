<?php

namespace App\Filament\Resources\ReportHandovers\Pages;

use App\Filament\Resources\ReportHandovers\ReportHandoverResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReportHandover extends EditRecord
{
    protected static string $resource = ReportHandoverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
