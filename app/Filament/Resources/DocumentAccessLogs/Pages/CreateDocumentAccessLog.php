<?php

namespace App\Filament\Resources\DocumentAccessLogs\Pages;

use App\Filament\Resources\DocumentAccessLogs\DocumentAccessLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentAccessLog extends CreateRecord
{
    protected static string $resource = DocumentAccessLogResource::class;
}
