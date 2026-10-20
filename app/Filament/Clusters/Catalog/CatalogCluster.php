<?php

namespace App\Filament\Clusters\Catalog;

use App\Enums\PermissionEnum;
use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class CatalogCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    public static function getNavigationLabel(): string
    {
        return __('Service Catalog');
    }

    public static function getClusterBreadcrumb(): ?string
    {
        return __('Service Catalog');
    }

    protected static ?string $clusterBreadcrumb = 'Katalog';

    protected static string|UnitEnum|null $navigationGroup = 'Katalog Layanan';

    protected static ?int $navigationSort = 5;

    public static function canAccess(): bool
    {
        return auth('employee')->user()?->can(PermissionEnum::CatalogManage->value) ?? false;
    }
}
