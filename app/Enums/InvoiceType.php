<?php

declare(strict_types=1);

namespace App\Enums;

enum InvoiceType: string
{
    case INDIVIDUAL = 'individual';
    case CORPORATE = 'corporate';

    public function label(): string
    {
        return match ($this) {
            self::INDIVIDUAL => 'Bireysel',
            self::CORPORATE => 'Kurumsal',
        };
    }
}
