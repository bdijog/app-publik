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

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('nip')
                    ->label('NIP')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email Dinas')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('laboratoryUnit.name')
                    ->label('Instalasi')
                    ->badge()
                    ->placeholder('Non-Laboratorium')
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label('Peran RBAC')
                    ->badge()
                    ->color('info')
                    ->separator(','),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('last_login_at')
                    ->label('Login Terakhir')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('Belum pernah')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('laboratory_unit_id')
                    ->label('Instalasi')
                    ->relationship('laboratoryUnit', 'name')
                    ->preload(),

                SelectFilter::make('roles')
                    ->label('Peran')
                    ->relationship('roles', 'name', fn ($q) => $q->where('guard_name', 'employee'))
                    ->preload(),

                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),

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
