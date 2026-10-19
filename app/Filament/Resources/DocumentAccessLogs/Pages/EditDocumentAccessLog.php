<?php

namespace App\Filament\Resources\DocumentAccessLogs\Pages;

use App\Filament\Resources\DocumentAccessLogs\DocumentAccessLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentAccessLog extends EditRecord
{
    protected static string $resource = DocumentAccessLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
