<?php

namespace App\Filament\Clusters\Catalog\Resources\ServicePackages;

use App\Filament\Clusters\Catalog\CatalogCluster;
use App\Filament\Clusters\Catalog\Resources\ServicePackages\Pages\CreateServicePackage;
use App\Filament\Clusters\Catalog\Resources\ServicePackages\Pages\EditServicePackage;
use App\Filament\Clusters\Catalog\Resources\ServicePackages\Pages\ListServicePackages;
use App\Filament\Clusters\Catalog\Resources\ServicePackages\Schemas\ServicePackageForm;
use App\Filament\Clusters\Catalog\Resources\ServicePackages\Tables\ServicePackagesTable;
use App\Models\ServicePackage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServicePackageResource extends Resource
{
    protected static ?string $model = ServicePackage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static ?string $cluster = CatalogCluster::class;

    public static function getModelLabel(): string
    {
        return __('Examination Package');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Examination Packages');
    }

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ServicePackageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicePackagesTable::configure($table);
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
            'index' => ListServicePackages::route('/'),
            'create' => CreateServicePackage::route('/create'),
            'edit' => EditServicePackage::route('/{record}/edit'),
        ];
    }
}
