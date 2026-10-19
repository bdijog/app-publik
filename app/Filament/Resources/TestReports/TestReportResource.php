<?php

namespace App\Filament\Resources\TestReports;

use App\Filament\Resources\TestReports\Pages\CreateTestReport;
use App\Filament\Resources\TestReports\Pages\EditTestReport;
use App\Filament\Resources\TestReports\Pages\ListTestReports;
use App\Filament\Resources\TestReports\Schemas\TestReportForm;
use App\Filament\Resources\TestReports\Tables\TestReportsTable;
use App\Models\TestReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TestReportResource extends Resource
{
    protected static ?string $model = TestReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Laporan Hasil Uji (LHU)';

    protected static ?string $modelLabel = 'Laporan Hasil Uji (LHU)';

    protected static ?string $pluralModelLabel = 'Penerbitan LHU';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'report_number';

    public static function form(Schema $schema): Schema
    {
        return TestReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TestReportsTable::configure($table);
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
            'index' => ListTestReports::route('/'),
            'create' => CreateTestReport::route('/create'),
            'edit' => EditTestReport::route('/{record}/edit'),
        ];
    }
}
