<?php

namespace App\Filament\Clusters\Catalog\Resources\SampleTypes\Pages;

use App\Filament\Clusters\Catalog\Resources\SampleTypes\SampleTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSampleTypes extends ListRecords
{
    protected static string $resource = SampleTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
