<?php

namespace App\Filament\Clusters\Catalog\Resources\CompletionEstimates\Pages;

use App\Filament\Clusters\Catalog\Resources\CompletionEstimates\CompletionEstimateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompletionEstimate extends EditRecord
{
    protected static string $resource = CompletionEstimateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
