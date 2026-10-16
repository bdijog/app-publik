<?php

namespace App\Enums;

enum SampleDecision: string
{
    case Accepted = 'accepted';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Accepted => 'Diterima',
            self::Rejected => 'Ditolak',
        };
    }
}
