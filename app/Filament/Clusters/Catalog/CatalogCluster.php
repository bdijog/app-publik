<?php

namespace App\Filament\Clusters\Catalog;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class CatalogCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Katalog Layanan';

    protected static ?string $clusterBreadcrumb = 'Katalog';

    protected static string|UnitEnum|null $navigationGroup = 'Katalog Layanan';

    protected static ?int $navigationSort = 5;
}
