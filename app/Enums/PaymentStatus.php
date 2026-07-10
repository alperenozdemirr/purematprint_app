<?php
namespace App\Enums;

enum PaymentStatus: string
{
    case REFUNDED = 'refunded';
    case COMPLETED = 'completed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::COMPLETED => 'Tamamlandı',
            self::REFUNDED => 'İade Edildi',
        };
    }
}
