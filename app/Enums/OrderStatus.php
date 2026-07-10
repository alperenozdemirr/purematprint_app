<?php
namespace App\Enums;

enum OrderStatus: string
{
    case PREPARING = 'preparing';
    case SHIPPED = 'shipped';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::PREPARING => 'Hazırlanıyor',
            self::SHIPPED => 'Kargoya Verildi',
            self::COMPLETED => 'Tamamlandı',
            self::CANCELLED => 'İptal',
        };
    }
}
