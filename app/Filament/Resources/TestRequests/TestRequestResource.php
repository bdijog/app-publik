<?php

namespace App\Filament\Resources\TestRequests;

use App\Filament\Resources\TestRequests\Pages\CreateTestRequest;
use App\Filament\Resources\TestRequests\Pages\EditTestRequest;
use App\Filament\Resources\TestRequests\Pages\ListTestRequests;
use App\Filament\Resources\TestRequests\Pages\ViewTestRequest;
use App\Filament\Resources\TestRequests\Schemas\TestRequestForm;
use App\Filament\Resources\TestRequests\Schemas\TestRequestInfolist;
use App\Filament\Resources\TestRequests\Tables\TestRequestsTable;
use App\Models\TestRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class TestRequestResource extends Resource
{
    protected static ?string $model = TestRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Pelayanan & Permohonan';

    public static function getModelLabel(): string
    {
        return __('Test Request');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Test Requests (FPPS)');
    }

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'fpps_number';

    public static function form(Schema $schema): Schema
    {
        return TestRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TestRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TestRequestsTable::configure($table);
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
            'index' => ListTestRequests::route('/'),
            'create' => CreateTestRequest::route('/create'),
            'view' => ViewTestRequest::route('/{record}'),
            'edit' => EditTestRequest::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
