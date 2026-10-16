<?php

namespace App\Enums;

enum DocumentAccessAction: string
{
    case View = 'view';
    case Download = 'download';
    case Print = 'print';

    public function label(): string
    {
        return match ($this) {
            self::View => 'Lihat',
            self::Download => 'Unduh',
            self::Print => 'Cetak',
        };
    }
}
