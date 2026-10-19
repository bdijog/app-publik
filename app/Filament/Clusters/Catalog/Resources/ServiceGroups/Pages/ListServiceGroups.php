<?php

namespace App\Filament\Clusters\Catalog\Resources\ServiceGroups\Pages;

use App\Filament\Clusters\Catalog\Resources\ServiceGroups\ServiceGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServiceGroups extends ListRecords
{
    protected static string $resource = ServiceGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
