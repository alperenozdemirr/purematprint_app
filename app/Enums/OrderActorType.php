<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderActorType: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Yönetici',
            self::CUSTOMER => 'Müşteri',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
