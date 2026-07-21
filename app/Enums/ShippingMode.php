<?php

namespace App\Enums;

enum ShippingMode: string
{
    case FREE = 'free';
    case PAID = 'paid';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::FREE => 'Tüm Siparişlerde Ücretsiz',
            self::PAID => 'Ücretli Kargo',
        };
    }
}
