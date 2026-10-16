<?php

namespace App\Enums;

enum RegistrationChannel: string
{
    case Online = 'online';
    case WhatsApp = 'whatsapp';
    case Counter = 'counter';

    public function label(): string
    {
        return match ($this) {
            self::Online => 'Online',
            self::WhatsApp => 'WhatsApp',
            self::Counter => 'Loket Pelayanan',
        };
    }
}
