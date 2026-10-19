<?php

namespace App\Filament\Clusters\Catalog\Resources\SampleTypes;

use App\Filament\Clusters\Catalog\CatalogCluster;
use App\Filament\Clusters\Catalog\Resources\SampleTypes\Pages\CreateSampleType;
use App\Filament\Clusters\Catalog\Resources\SampleTypes\Pages\EditSampleType;
use App\Filament\Clusters\Catalog\Resources\SampleTypes\Pages\ListSampleTypes;
use App\Filament\Clusters\Catalog\Resources\SampleTypes\Schemas\SampleTypeForm;
use App\Filament\Clusters\Catalog\Resources\SampleTypes\Tables\SampleTypesTable;
use App\Models\SampleType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SampleTypeResource extends Resource
{
    protected static ?string $model = SampleType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $cluster = CatalogCluster::class;

    protected static ?string $modelLabel = 'Jenis Sampel';

    protected static ?string $pluralModelLabel = 'Jenis Sampel & Persyaratan';

    protected static ?int $navigationSort = 7;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SampleTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SampleTypesTable::configure($table);
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
            'index' => ListSampleTypes::route('/'),
            'create' => CreateSampleType::route('/create'),
            'edit' => EditSampleType::route('/{record}/edit'),
        ];
    }
}
