<?php

namespace App\Filament\Clusters\Catalog\Resources\Tariffs\Pages;

use App\Filament\Clusters\Catalog\Resources\Tariffs\TariffResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTariffs extends ListRecords
{
    protected static string $resource = TariffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
