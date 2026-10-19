<?php

namespace App\Filament\Resources\TestRequests\Pages;

use App\Filament\Resources\TestRequests\TestRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTestRequest extends CreateRecord
{
    protected static string $resource = TestRequestResource::class;
}
