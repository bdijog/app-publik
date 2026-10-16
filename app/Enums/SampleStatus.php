<?php

namespace App\Enums;

enum SampleStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Penerimaan',
            self::Accepted => 'Diterima',
            self::Rejected => 'Ditolak',
        };
    }
}
