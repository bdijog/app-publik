<?php

namespace App\Enums;

enum CustomerType: string
{
    case Individual = 'individual';
    case Organization = 'organization';
    case HealthFacility = 'health_facility';

    public function label(): string
    {
        return match ($this) {
            self::Individual => 'Perorangan',
            self::Organization => 'Instansi',
            self::HealthFacility => 'Fasilitas Kesehatan',
        };
    }
}
