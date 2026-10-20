<?php

namespace App\Filament\Clusters\Catalog\Resources\TestParameters;

use App\Filament\Clusters\Catalog\CatalogCluster;
use App\Filament\Clusters\Catalog\Resources\TestParameters\Pages\CreateTestParameter;
use App\Filament\Clusters\Catalog\Resources\TestParameters\Pages\EditTestParameter;
use App\Filament\Clusters\Catalog\Resources\TestParameters\Pages\ListTestParameters;
use App\Filament\Clusters\Catalog\Resources\TestParameters\Schemas\TestParameterForm;
use App\Filament\Clusters\Catalog\Resources\TestParameters\Tables\TestParametersTable;
use App\Models\TestParameter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TestParameterResource extends Resource
{
    protected static ?string $model = TestParameter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static ?string $cluster = CatalogCluster::class;

    public static function getModelLabel(): string
    {
        return __('Test Parameter');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Test Parameters');
    }

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TestParameterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TestParametersTable::configure($table);
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
            'index' => ListTestParameters::route('/'),
            'create' => CreateTestParameter::route('/create'),
            'edit' => EditTestParameter::route('/{record}/edit'),
        ];
    }
}
