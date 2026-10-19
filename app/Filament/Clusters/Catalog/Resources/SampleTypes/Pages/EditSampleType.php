<?php

namespace App\Filament\Clusters\Catalog\Resources\SampleTypes\Pages;

use App\Filament\Clusters\Catalog\Resources\SampleTypes\SampleTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSampleType extends EditRecord
{
    protected static string $resource = SampleTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
