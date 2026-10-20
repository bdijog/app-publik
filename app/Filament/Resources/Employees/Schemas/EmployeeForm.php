<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Enums\RoleEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pegawai')
                    ->description('Data identitas dan penempatan instalasi pegawai Balai Labkesmas Ambon')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label(__('Full Name & Title'))
                                ->required()
                                ->maxLength(255),

                            TextInput::make('nip')
                                ->label(__('Employee Identification Number (NIP)'))
                                ->nullable()
                                ->unique(ignoreRecord: true)
                                ->maxLength(30),

                            TextInput::make('email')
                                ->label(__('Official Email (Login Username)'))
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            TextInput::make('phone')
                                ->label(__('Phone / WhatsApp Number'))
                                ->tel()
                                ->nullable()
                                ->maxLength(20),

                            Select::make('laboratory_unit_id')
                                ->label(__('Placement Unit / Installation'))
                                ->relationship('laboratoryUnit', 'name')
                                ->searchable()
                                ->preload()
                                ->nullable(),

                            Select::make('roles')
                                ->label(__('Role (RBAC Role)'))
                                ->relationship('roles', 'name', fn ($query) => $query->where('guard_name', 'employee'))
                                ->getOptionLabelFromRecordUsing(fn ($record) => (RoleEnum::tryFrom($record->name)?->label() ?? $record->name)." ({$record->name})")
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->required(),
                        ]),
                    ]),

                Section::make('Autentikasi & Keamanan')
                    ->description('Pengaturan kata sandi dan status akses akun pegawai')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('password')
                                ->label(__('Password'))
                                ->password()
                                ->revealable()
                                ->rule(Password::default())
                                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                ->dehydrated(fn ($state) => filled($state))
                                ->required(fn (string $operation): bool => $operation === 'create')
                                ->helperText(fn (string $operation): string => $operation === 'edit'
                                    ? 'Kosongkan jika tidak ingin mengubah kata sandi'
                                    : 'Wajib diisi saat membuat akun pegawai baru'),

                            Toggle::make('is_active')
                                ->label(__('Active Account'))
                                ->helperText('Pegawai non-aktif tidak dapat masuk ke sistem panel')
                                ->default(true)
                                ->inline(false),
                        ]),
                    ]),
            ]);
    }
}
