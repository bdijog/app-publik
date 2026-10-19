<?php

namespace App\Filament\Clusters\Catalog\Resources\ServicePackages\Pages;

use App\Filament\Clusters\Catalog\Resources\ServicePackages\ServicePackageResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditServicePackage extends EditRecord
{
    protected static string $resource = ServicePackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
