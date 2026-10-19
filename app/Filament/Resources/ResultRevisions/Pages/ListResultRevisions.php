<?php

namespace App\Filament\Resources\ResultRevisions\Pages;

use App\Filament\Resources\ResultRevisions\ResultRevisionResource;
use Filament\Resources\Pages\ListRecords;

class ListResultRevisions extends ListRecords
{
    protected static string $resource = ResultRevisionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
