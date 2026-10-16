<?php

namespace App\Enums;

enum ResultType: string
{
    case Numeric = 'numeric';
    case Text = 'text';

    public function label(): string
    {
        return match ($this) {
            self::Numeric => 'Angka / Numerik',
            self::Text => 'Teks / Kualitatif',
        };
    }
}
