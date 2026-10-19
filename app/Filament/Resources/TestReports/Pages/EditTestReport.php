<?php

namespace App\Filament\Resources\TestReports\Pages;

use App\Filament\Resources\TestReports\TestReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTestReport extends EditRecord
{
    protected static string $resource = TestReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
