<?php

namespace App\Enums;

enum EstimateStartPoint: string
{
    case Payment = 'payment';
    case Receipt = 'receipt';

    public function label(): string
    {
        return match ($this) {
            self::Payment => 'Sejak Pembayaran Registrasi',
            self::Receipt => 'Sejak Penerimaan Sampel',
        };
    }
}
