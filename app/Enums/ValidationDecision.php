<?php

namespace App\Enums;

enum ValidationDecision: string
{
    case Approved = 'approved';
    case Returned = 'returned';

    public function label(): string
    {
        return match ($this) {
            self::Approved => 'Disetujui',
            self::Returned => 'Dikembalikan',
        };
    }
}
