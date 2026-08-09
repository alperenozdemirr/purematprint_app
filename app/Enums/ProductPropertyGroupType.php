<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductPropertyGroupType: string
{
    case SINGLE = 'single';
    case MULTIPLE = 'multiple';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::SINGLE => 'Tek seçim',
            self::MULTIPLE => 'Çoklu seçim',
        };
    }
}
