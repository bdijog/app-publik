<?php

namespace App\Filament\Clusters\Catalog\Resources\ServicePackages\Pages;

use App\Filament\Clusters\Catalog\Resources\ServicePackages\ServicePackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServicePackages extends ListRecords
{
    protected static string $resource = ServicePackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
