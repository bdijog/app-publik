<?php

namespace App\Filament\Resources\Roles\Tables;

use App\Enums\RoleEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Role Name & Code'))
                    ->formatStateUsing(fn ($state): string => RoleEnum::tryFrom((string) $state)?->label() ?? (string) $state)
                    ->description(function (Role $record): string {
                        $enum = RoleEnum::tryFrom($record->name);

                        return $enum ? "{$enum->description()} [Kode: {$record->name}]" : "Kode: {$record->name}";
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('guard_name')
                    ->label(__('Guard'))
                    ->badge()
                    ->color('gray'),

                TextColumn::make('permissions_count')
                    ->counts('permissions')
                    ->label(__('Permissions Count'))
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
