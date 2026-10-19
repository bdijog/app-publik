<?php

namespace App\Filament\Resources\ReportHandovers;

use App\Filament\Resources\ReportHandovers\Pages\CreateReportHandover;
use App\Filament\Resources\ReportHandovers\Pages\EditReportHandover;
use App\Filament\Resources\ReportHandovers\Pages\ListReportHandovers;
use App\Filament\Resources\ReportHandovers\Schemas\ReportHandoverForm;
use App\Filament\Resources\ReportHandovers\Tables\ReportHandoversTable;
use App\Models\ReportHandover;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ReportHandoverResource extends Resource
{
    protected static ?string $model = ReportHandover::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static string|UnitEnum|null $navigationGroup = 'Laporan Hasil Uji (LHU)';

    protected static ?string $modelLabel = 'Penyerahan LHU';

    protected static ?string $pluralModelLabel = 'Penyerahan LHU ke Pelanggan';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ReportHandoverForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReportHandoversTable::configure($table);
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
            'index' => ListReportHandovers::route('/'),
            'create' => CreateReportHandover::route('/create'),
            'edit' => EditReportHandover::route('/{record}/edit'),
        ];
    }
}
