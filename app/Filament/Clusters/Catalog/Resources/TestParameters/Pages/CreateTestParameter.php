<?php

namespace App\Filament\Clusters\Catalog\Resources\TestParameters\Pages;

use App\Filament\Clusters\Catalog\Resources\TestParameters\TestParameterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTestParameter extends CreateRecord
{
    protected static string $resource = TestParameterResource::class;
}
