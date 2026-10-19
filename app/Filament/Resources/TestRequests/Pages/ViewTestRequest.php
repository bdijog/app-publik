<?php

namespace App\Filament\Resources\TestRequests\Pages;

use App\Filament\Resources\TestRequests\TestRequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTestRequest extends ViewRecord
{
    protected static string $resource = TestRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
