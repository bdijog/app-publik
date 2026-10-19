<?php

namespace App\Filament\Clusters\Catalog\Resources\CompletionEstimates\Pages;

use App\Filament\Clusters\Catalog\Resources\CompletionEstimates\CompletionEstimateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompletionEstimates extends ListRecords
{
    protected static string $resource = CompletionEstimateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
