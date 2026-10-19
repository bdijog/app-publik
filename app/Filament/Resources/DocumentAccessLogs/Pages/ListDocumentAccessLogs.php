<?php

namespace App\Filament\Resources\DocumentAccessLogs\Pages;

use App\Filament\Resources\DocumentAccessLogs\DocumentAccessLogResource;
use Filament\Resources\Pages\ListRecords;

class ListDocumentAccessLogs extends ListRecords
{
    protected static string $resource = DocumentAccessLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
