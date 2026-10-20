<?php

namespace App\Filament\Resources\Permissions\Tables;

use App\Enums\PermissionEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;

class PermissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Kode Izin')
                    ->badge()
                    ->color('warning')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('label')
                    ->label('Deskripsi Izin')
                    ->state(fn (Permission $record): string => PermissionEnum::tryFrom($record->name)?->label() ?? '-')
                    ->searchable(false),

                TextColumn::make('group')
                    ->label('Klaster Modul')
                    ->state(fn (Permission $record): string => PermissionEnum::tryFrom($record->name)?->group() ?? 'Lainnya')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Dashboard & Analitik' => 'info',
                        'Pengguna & Hak Akses' => 'danger',
                        'Katalog & Konfigurasi Layanan' => 'gray',
                        'Pendaftaran & Permohonan Uji', 'Pembayaran & Keuangan' => 'primary',
                        'Penerimaan Sampel Fisik', 'Distribusi Pekerjaan Uji' => 'warning',
                        'Pengujian & Validasi Hasil' => 'purple',
                        'Laporan Hasil Uji (LHU) & Rekapitulasi' => 'success',
                        default => 'gray',
                    })
                    ->searchable(false),

                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('roles_count')
                    ->counts('roles')
                    ->label('Digunakan di Peran')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
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
