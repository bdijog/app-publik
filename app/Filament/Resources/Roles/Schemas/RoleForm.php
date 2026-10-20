<?php

namespace App\Filament\Resources\Roles\Schemas;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Peran')
                    ->description('Nama dan cakupan guard peran sistem')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label(__('Role Name / Code'))
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->datalist(RoleEnum::values())
                                ->helperText('Contoh kode peran: '.implode(', ', RoleEnum::values())),

                            TextInput::make('guard_name')
                                ->label(__('Guard'))
                                ->default('employee')
                                ->disabled()
                                ->dehydrated()
                                ->required(),
                        ]),
                    ]),

                Section::make('Hak Akses (Permissions)')
                    ->description('Pilih izin terpadu (CRUD) yang dimiliki oleh peran ini')
                    ->schema([
                        CheckboxList::make('permissions')
                            ->label(__('Integrated Permissions List'))
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => $query->where('guard_name', 'employee')->orderBy('name'),
                            )
                            ->getOptionLabelFromRecordUsing(function ($record): string {
                                $enum = PermissionEnum::tryFrom($record->name);
                                if (! $enum) {
                                    return $record->name;
                                }

                                return "[{$enum->group()}] {$enum->label()} ({$record->name})";
                            })
                            ->columns(2)
                            ->searchable()
                            ->bulkToggleable(),
                    ]),
            ]);
    }
}
