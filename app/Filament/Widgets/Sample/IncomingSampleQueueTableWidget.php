<?php

namespace App\Filament\Widgets\Sample;

use App\Enums\SampleStatus;
use App\Models\Employee;
use App\Models\Sample;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class IncomingSampleQueueTableWidget extends TableWidget
{
    protected static ?int $sort = 10;

    protected int|string|array $columnSpan = 'half';

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = Auth::guard('employee')->user();

        return $user && ($user->hasRole('sample_receiver') || $user->hasRole('service_officer') || $user->hasRole('admin'));
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Antrean Sampel Menunggu Verifikasi Kelaikan Fisik')
            ->query(
                Sample::query()
                    ->where('status', SampleStatus::Pending->value)
                    ->with(['testRequest', 'sampleType'])
                    ->latest('created_at')
            )
            ->columns([
                TextColumn::make('sample_code')
                    ->label('Kode Sampel')
                    ->badge()
                    ->searchable(),

                TextColumn::make('testRequest.fpps_number')
                    ->label('No. FPPS')
                    ->searchable(),

                TextColumn::make('sampleType.name')
                    ->label('Jenis Sampel')
                    ->limit(20)
                    ->searchable(),

                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->formatStateUsing(fn ($record) => "{$record->quantity} {$record->unit}"),

                TextColumn::make('sampleType.container_requirement')
                    ->label('Syarat Wadah')
                    ->limit(20)
                    ->placeholder('-'),
            ])
            ->paginated([5, 10]);
    }
}
