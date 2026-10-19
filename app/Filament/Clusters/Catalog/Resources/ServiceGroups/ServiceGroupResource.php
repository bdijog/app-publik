<?php

namespace App\Filament\Clusters\Catalog\Resources\ServiceGroups;

use App\Filament\Clusters\Catalog\CatalogCluster;
use App\Filament\Clusters\Catalog\Resources\ServiceGroups\Pages\CreateServiceGroup;
use App\Filament\Clusters\Catalog\Resources\ServiceGroups\Pages\EditServiceGroup;
use App\Filament\Clusters\Catalog\Resources\ServiceGroups\Pages\ListServiceGroups;
use App\Filament\Clusters\Catalog\Resources\ServiceGroups\Schemas\ServiceGroupForm;
use App\Filament\Clusters\Catalog\Resources\ServiceGroups\Tables\ServiceGroupsTable;
use App\Models\ServiceGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceGroupResource extends Resource
{
    protected static ?string $model = ServiceGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $cluster = CatalogCluster::class;

    protected static ?string $modelLabel = 'Kelompok Layanan';

    protected static ?string $pluralModelLabel = 'Kelompok Layanan';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ServiceGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceGroupsTable::configure($table);
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
            'index' => ListServiceGroups::route('/'),
            'create' => CreateServiceGroup::route('/create'),
            'edit' => EditServiceGroup::route('/{record}/edit'),
        ];
    }
}
