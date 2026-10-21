<?php

namespace App\Filament\Widgets\Service;

use App\Enums\PaymentStatus;
use App\Models\Employee;
use App\Models\TestRequest;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class PendingPaymentsTableWidget extends TableWidget
{
    protected static ?int $sort = 7;

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
            ->heading('Tagihan Menunggu Pembayaran')
            ->query(
                TestRequest::query()
                    ->where('payment_status', PaymentStatus::Unpaid->value)
                    ->latest('registered_at')
            )
            ->columns([
                TextColumn::make('fpps_number')
                    ->label('No. FPPS')
                    ->searchable()
                    ->badge(),

                TextColumn::make('customer.name')
                    ->label('Pemohon')
                    ->limit(18)
                    ->searchable(),

                TextColumn::make('total_amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('registered_at')
                    ->label('Waktu Registrasi')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->paginated([5, 10]);
    }
}
