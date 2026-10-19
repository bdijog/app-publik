<?php

namespace App\Filament\Clusters\Catalog\Resources\TestParameters\Pages;

use App\Filament\Clusters\Catalog\Resources\TestParameters\TestParameterResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditTestParameter extends EditRecord
{
    protected static string $resource = TestParameterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
