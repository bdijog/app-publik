<?php

namespace App\Filament\Resources\DocumentAccessLogs;

use App\Filament\Resources\DocumentAccessLogs\Pages\ListDocumentAccessLogs;
use App\Filament\Resources\DocumentAccessLogs\Schemas\DocumentAccessLogForm;
use App\Filament\Resources\DocumentAccessLogs\Tables\DocumentAccessLogsTable;
use App\Models\DocumentAccessLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DocumentAccessLogResource extends Resource
{
    protected static ?string $model = DocumentAccessLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;

    protected static string|UnitEnum|null $navigationGroup = 'Audit & Jejak Sistem';

    public static function getModelLabel(): string
    {
        return __('Document Access Log');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Document / QR Access Logs');
    }

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return DocumentAccessLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentAccessLogsTable::configure($table);
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
            'index' => ListDocumentAccessLogs::route('/'),
        ];
    }
}
