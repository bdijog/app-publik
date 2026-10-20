<?php

namespace App\Filament\Clusters\Catalog\Resources\LaboratoryUnits;

use App\Filament\Clusters\Catalog\CatalogCluster;
use App\Filament\Clusters\Catalog\Resources\LaboratoryUnits\Pages\CreateLaboratoryUnit;
use App\Filament\Clusters\Catalog\Resources\LaboratoryUnits\Pages\EditLaboratoryUnit;
use App\Filament\Clusters\Catalog\Resources\LaboratoryUnits\Pages\ListLaboratoryUnits;
use App\Filament\Clusters\Catalog\Resources\LaboratoryUnits\Schemas\LaboratoryUnitForm;
use App\Filament\Clusters\Catalog\Resources\LaboratoryUnits\Tables\LaboratoryUnitsTable;
use App\Models\LaboratoryUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaboratoryUnitResource extends Resource
{
    protected static ?string $model = LaboratoryUnit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $cluster = CatalogCluster::class;

    public static function getModelLabel(): string
    {
        return __('Laboratory Unit');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Laboratory Units');
    }

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return LaboratoryUnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaboratoryUnitsTable::configure($table);
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
            'index' => ListLaboratoryUnits::route('/'),
            'create' => CreateLaboratoryUnit::route('/create'),
            'edit' => EditLaboratoryUnit::route('/{record}/edit'),
        ];
    }
}
