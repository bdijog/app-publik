<?php

namespace App\Enums;

enum TestResultStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Returned = 'returned';
    case Validated = 'validated';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draf Hasil',
            self::Submitted => 'Menunggu Validasi',
            self::Returned => 'Dikembalikan untuk Perbaikan',
            self::Validated => 'Tervalidasi',
        };
    }
}
