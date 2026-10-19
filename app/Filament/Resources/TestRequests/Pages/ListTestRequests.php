<?php

namespace App\Filament\Resources\TestRequests\Pages;

use App\Filament\Resources\TestRequests\TestRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTestRequests extends ListRecords
{
    protected static string $resource = TestRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
