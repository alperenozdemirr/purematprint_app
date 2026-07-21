<?php

namespace App\Enums;

enum DiscountType: string
{
    case PERCENT = 'percent';
    case FIXED = 'fixed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::PERCENT => 'Yüzde (%)',
            self::FIXED => 'Sabit Tutar (₺)',
        };
    }
}
