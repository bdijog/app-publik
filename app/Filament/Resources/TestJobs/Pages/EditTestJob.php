<?php

namespace App\Filament\Resources\TestJobs\Pages;

use App\Filament\Resources\TestJobs\TestJobResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTestJob extends EditRecord
{
    protected static string $resource = TestJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
