<?php

namespace App\Enums;

enum DiscountScope: string
{
    case FIRST_ORDER = 'first_order';
    case ALL_ORDERS = 'all_orders';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::FIRST_ORDER => 'İlk Sipariş',
            self::ALL_ORDERS => 'Tüm Siparişler',
        };
    }
}
