<?php

namespace App\Filament\Clusters\Catalog\Resources\Tariffs;

use App\Filament\Clusters\Catalog\CatalogCluster;
use App\Filament\Clusters\Catalog\Resources\Tariffs\Pages\CreateTariff;
use App\Filament\Clusters\Catalog\Resources\Tariffs\Pages\EditTariff;
use App\Filament\Clusters\Catalog\Resources\Tariffs\Pages\ListTariffs;
use App\Filament\Clusters\Catalog\Resources\Tariffs\Schemas\TariffForm;
use App\Filament\Clusters\Catalog\Resources\Tariffs\Tables\TariffsTable;
use App\Models\Tariff;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TariffResource extends Resource
{
    protected static ?string $model = Tariff::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $cluster = CatalogCluster::class;

    public static function getModelLabel(): string
    {
        return __('Service Tariff');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Service Retribution Tariffs');
    }

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return TariffForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TariffsTable::configure($table);
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
            'index' => ListTariffs::route('/'),
            'create' => CreateTariff::route('/create'),
            'edit' => EditTariff::route('/{record}/edit'),
        ];
    }
}
