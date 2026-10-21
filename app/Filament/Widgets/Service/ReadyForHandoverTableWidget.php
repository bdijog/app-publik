<?php

namespace App\Filament\Widgets\Service;

use App\Enums\ReportStatus;
use App\Models\Employee;
use App\Models\TestReport;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class ReadyForHandoverTableWidget extends TableWidget
{
    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && ($user->hasRole('service_officer') || $user->hasRole('admin'));
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Laporan Hasil Uji (LHU) Final Siap Diserahkan ke Pelanggan')
            ->query(
                TestReport::query()
                    ->where('status', ReportStatus::Final->value)
                    ->whereDoesntHave('handovers')
                    ->latest('issued_at')
            )
            ->columns([
                TextColumn::make('report_number')
                    ->label('Nomor LHU')
                    ->searchable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('testRequest.fpps_number')
                    ->label('Nomor FPPS')
                    ->searchable(),

                TextColumn::make('testRequest.customer.name')
                    ->label('Nama Pemohon')
                    ->searchable(),

                TextColumn::make('issued_at')
                    ->label('Tanggal Terbit')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('handover_status')
                    ->label('Status Serah Terima')
                    ->badge()
                    ->default('Siap Diserahkan')
                    ->color('warning'),
            ])
            ->paginated([5, 10]);
    }
}
