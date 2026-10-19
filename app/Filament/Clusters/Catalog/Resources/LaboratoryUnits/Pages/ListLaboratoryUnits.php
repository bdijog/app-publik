<?php

namespace App\Filament\Clusters\Catalog\Resources\LaboratoryUnits\Pages;

use App\Filament\Clusters\Catalog\Resources\LaboratoryUnits\LaboratoryUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLaboratoryUnits extends ListRecords
{
    protected static string $resource = LaboratoryUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
