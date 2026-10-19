<?php

namespace App\Filament\Resources\TestJobs\Pages;

use App\Filament\Resources\TestJobs\TestJobResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTestJob extends CreateRecord
{
    protected static string $resource = TestJobResource::class;
}
