<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Employee Name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('nip')
                    ->label(__('NIP'))
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label(__('Official Email'))
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('laboratoryUnit.name')
                    ->label(__('Laboratory Unit'))
                    ->badge()
                    ->placeholder('Non-Laboratorium')
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label(__('RBAC Role'))
                    ->badge()
                    ->color('info')
                    ->separator(','),

                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean()
                    ->sortable(),

                TextColumn::make('last_login_at')
                    ->label(__('Last Login'))
                    ->dateTime('d M Y, H:i')
                    ->placeholder('Belum pernah')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('laboratory_unit_id')
                    ->label(__('Laboratory Unit'))
                    ->relationship('laboratoryUnit', 'name')
                    ->preload(),

                SelectFilter::make('roles')
                    ->label(__('Role'))
                    ->relationship('roles', 'name', fn (Builder $query) => $query->where('guard_name', 'employee'))
                    ->preload(),

                TernaryFilter::make('is_active')
                    ->label(__('Active Status')),

                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
