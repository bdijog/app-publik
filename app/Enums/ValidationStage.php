<?php

namespace App\Enums;

enum ValidationStage: string
{
    case ManagerLab = 'manager_lab';
    case Recommendation = 'recommendation';

    public function label(): string
    {
        return match ($this) {
            self::ManagerLab => 'Validasi Manager Lab',
            self::Recommendation => 'Validasi Rekomendasi',
        };
    }
}
