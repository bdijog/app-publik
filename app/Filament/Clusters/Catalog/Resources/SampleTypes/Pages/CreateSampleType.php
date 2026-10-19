<?php

namespace App\Filament\Clusters\Catalog\Resources\SampleTypes\Pages;

use App\Filament\Clusters\Catalog\Resources\SampleTypes\SampleTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSampleType extends CreateRecord
{
    protected static string $resource = SampleTypeResource::class;
}
