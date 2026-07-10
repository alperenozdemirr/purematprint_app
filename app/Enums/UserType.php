<?php
namespace App\Enums;

enum UserType: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Yönetici',
            self::USER => 'Müşteri',
        };
    }
}
