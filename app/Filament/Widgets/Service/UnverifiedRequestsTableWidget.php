<?php

namespace App\Filament\Widgets\Service;

use App\Enums\RegistrationChannel;
use App\Enums\TestRequestStatus;
use App\Models\Employee;
use App\Models\TestRequest;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class UnverifiedRequestsTableWidget extends TableWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'half';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && ($user->hasRole('service_officer') || $user->hasRole('admin'));
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Antrean Permohonan Baru Menunggu Verifikasi')
            ->query(
                TestRequest::query()
                    ->whereIn('status', [
                        TestRequestStatus::Draft->value,
                        TestRequestStatus::Submitted->value,
                    ])
                    ->latest('registered_at')
            )
            ->columns([
                TextColumn::make('fpps_number')
                    ->label('Nomor FPPS')
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('customer.name')
                    ->label('Nama Pemohon')
                    ->limit(20)
                    ->searchable(),

                TextColumn::make('channel')
                    ->label('Kanal')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state instanceof RegistrationChannel ? $state->label() : (RegistrationChannel::tryFrom((string) $state)?->label() ?? (string) $state))
                    ->color(fn ($state): string => match ($state instanceof RegistrationChannel ? $state->value : (string) $state) {
                        'online' => 'info',
                        'whatsapp' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('registered_at')
                    ->label('Waktu Daftar')
                    ->since()
                    ->sortable(),
            ])
            ->paginated([5, 10]);
    }
}
