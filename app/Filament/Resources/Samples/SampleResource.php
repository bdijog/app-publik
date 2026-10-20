<?php

namespace App\Filament\Resources\Samples;

use App\Filament\Resources\Samples\Pages\CreateSample;
use App\Filament\Resources\Samples\Pages\EditSample;
use App\Filament\Resources\Samples\Pages\ListSamples;
use App\Filament\Resources\Samples\Schemas\SampleForm;
use App\Filament\Resources\Samples\Tables\SamplesTable;
use App\Models\Sample;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SampleResource extends Resource
{
    protected static ?string $model = Sample::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static string|UnitEnum|null $navigationGroup = 'Penerimaan Sampel';

    public static function getModelLabel(): string
    {
        return __('Physical Sample');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Physical Samples');
    }

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'sample_code';

    public static function form(Schema $schema): Schema
    {
        return SampleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SamplesTable::configure($table);
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
            'index' => ListSamples::route('/'),
            'create' => CreateSample::route('/create'),
            'edit' => EditSample::route('/{record}/edit'),
        ];
    }
}
