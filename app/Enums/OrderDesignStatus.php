<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderDesignStatus: string
{
    case NONE = 'none';
    case AWAITING_APPROVAL = 'awaiting_approval';
    case REVISION_REQUESTED = 'revision_requested';
    case APPROVED = 'approved';

    public function label(): string
    {
        return match ($this) {
            self::NONE => 'Tasarım Yok',
            self::AWAITING_APPROVAL => 'Onay Bekleniyor',
            self::REVISION_REQUESTED => 'Revize Talep Edildi',
            self::APPROVED => 'Onaylandı',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
