<?php

namespace App\Filament\Clusters\Catalog\Resources\ServicePackages\Pages;

use App\Filament\Clusters\Catalog\Resources\ServicePackages\ServicePackageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServicePackage extends CreateRecord
{
    protected static string $resource = ServicePackageResource::class;
}
