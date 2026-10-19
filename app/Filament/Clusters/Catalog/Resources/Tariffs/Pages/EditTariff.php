<?php

namespace App\Filament\Clusters\Catalog\Resources\Tariffs\Pages;

use App\Filament\Clusters\Catalog\Resources\Tariffs\TariffResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTariff extends EditRecord
{
    protected static string $resource = TariffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
