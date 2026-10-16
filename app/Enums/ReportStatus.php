<?php

namespace App\Enums;

enum ReportStatus: string
{
    case Draft = 'draft';
    case Final = 'final';
    case Revised = 'revised';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draf LHU',
            self::Final => 'Final',
            self::Revised => 'Revisi',
        };
    }
}
