<?php

namespace App\Filament\Clusters\Catalog\Resources\ServiceGroups\Pages;

use App\Filament\Clusters\Catalog\Resources\ServiceGroups\ServiceGroupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceGroup extends CreateRecord
{
    protected static string $resource = ServiceGroupResource::class;
}
