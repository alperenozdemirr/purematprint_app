<?php
namespace App\Enums;

enum Status: string
{
    case ACTIVE = 'active';
    case PASSIVE = 'passive';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Aktif',
            self::PASSIVE => 'Pasif',
        };
    }
}
