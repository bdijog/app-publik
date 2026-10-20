<?php

namespace App\Filament\Clusters\Catalog\Resources\CompletionEstimates;

use App\Filament\Clusters\Catalog\CatalogCluster;
use App\Filament\Clusters\Catalog\Resources\CompletionEstimates\Pages\CreateCompletionEstimate;
use App\Filament\Clusters\Catalog\Resources\CompletionEstimates\Pages\EditCompletionEstimate;
use App\Filament\Clusters\Catalog\Resources\CompletionEstimates\Pages\ListCompletionEstimates;
use App\Filament\Clusters\Catalog\Resources\CompletionEstimates\Schemas\CompletionEstimateForm;
use App\Filament\Clusters\Catalog\Resources\CompletionEstimates\Tables\CompletionEstimatesTable;
use App\Models\CompletionEstimate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompletionEstimateResource extends Resource
{
    protected static ?string $model = CompletionEstimate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $cluster = CatalogCluster::class;

    public static function getModelLabel(): string
    {
        return __('Completion Estimate (SLA)');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Completion Estimates (SLA)');
    }

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return CompletionEstimateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompletionEstimatesTable::configure($table);
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
            'index' => ListCompletionEstimates::route('/'),
            'create' => CreateCompletionEstimate::route('/create'),
            'edit' => EditCompletionEstimate::route('/{record}/edit'),
        ];
    }
}
