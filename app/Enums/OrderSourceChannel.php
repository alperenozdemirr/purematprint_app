<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderSourceChannel: string
{
    case WEBSITE = 'website';
    case INSTAGRAM = 'instagram';
    case WHATSAPP = 'whatsapp';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::WEBSITE => 'Website',
            self::INSTAGRAM => 'Instagram',
            self::WHATSAPP => 'WhatsApp',
            self::OTHER => 'Diğer',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function default(): self
    {
        return self::WEBSITE;
    }
}
