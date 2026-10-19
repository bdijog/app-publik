<?php

namespace App\Filament\Resources\TestJobs\Pages;

use App\Filament\Resources\TestJobs\TestJobResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTestJobs extends ListRecords
{
    protected static string $resource = TestJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
