<?php

declare(strict_types=1);

namespace App\Enums;

enum AddressScope: string
{
    case DOMESTIC = 'domestic';
    case INTERNATIONAL = 'international';

    public function label(): string
    {
        return match ($this) {
            self::DOMESTIC => 'Yurt İçi',
            self::INTERNATIONAL => 'Yurt Dışı',
        };
    }
}
