<?php

namespace App\Filament\Clusters\Catalog\Resources\LaboratoryUnits\Pages;

use App\Filament\Clusters\Catalog\Resources\LaboratoryUnits\LaboratoryUnitResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLaboratoryUnit extends EditRecord
{
    protected static string $resource = LaboratoryUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
