<?php

namespace App\Filament\Resources\ResultRevisions\Pages;

use App\Filament\Resources\ResultRevisions\ResultRevisionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResultRevision extends EditRecord
{
    protected static string $resource = ResultRevisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
