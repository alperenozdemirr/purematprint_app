<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderDesignRequestType: string
{
    case DESIGN_UPLOADED = 'design_uploaded';
    case REVISION_REQUESTED = 'revision_requested';
    case APPROVED = 'approved';

    public function label(): string
    {
        return match ($this) {
            self::DESIGN_UPLOADED => 'Tasarım Yüklendi',
            self::REVISION_REQUESTED => 'Revize Talebi',
            self::APPROVED => 'Tasarım Onaylandı',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
