<?php

namespace App\Filament\Resources\ResultRevisions;

use App\Filament\Resources\ResultRevisions\Pages\ListResultRevisions;
use App\Filament\Resources\ResultRevisions\Schemas\ResultRevisionForm;
use App\Filament\Resources\ResultRevisions\Tables\ResultRevisionsTable;
use App\Models\ResultRevision;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ResultRevisionResource extends Resource
{
    protected static ?string $model = ResultRevision::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|UnitEnum|null $navigationGroup = 'Pengujian Laboratorium';

    protected static ?string $modelLabel = 'Riwayat Revisi Hasil';

    protected static ?string $pluralModelLabel = 'Riwayat Revisi Hasil Uji';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return ResultRevisionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResultRevisionsTable::configure($table);
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
            'index' => ListResultRevisions::route('/'),
        ];
    }
}
