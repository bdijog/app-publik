<?php

namespace App\Filament\Resources\TestJobs;

use App\Filament\Resources\TestJobs\Pages\CreateTestJob;
use App\Filament\Resources\TestJobs\Pages\EditTestJob;
use App\Filament\Resources\TestJobs\Pages\ListTestJobs;
use App\Filament\Resources\TestJobs\Schemas\TestJobForm;
use App\Filament\Resources\TestJobs\Tables\TestJobsTable;
use App\Models\TestJob;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TestJobResource extends Resource
{
    protected static ?string $model = TestJob::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Pengujian Laboratorium';

    public static function getModelLabel(): string
    {
        return __('Work Order (SPK)');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Test Work Orders');
    }

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'job_number';

    public static function form(Schema $schema): Schema
    {
        return TestJobForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TestJobsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTestJobs::route('/'),
            'create' => CreateTestJob::route('/create'),
            'edit' => EditTestJob::route('/{record}/edit'),
        ];
    }
}
