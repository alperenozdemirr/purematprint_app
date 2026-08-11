<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderDesignType: string
{
    case READY_FILE = 'ready_file';
    case ADAPT_DESIGN = 'adapt_design';
    case FROM_SCRATCH = 'from_scratch';

    public function label(): string
    {
        return match ($this) {
            self::READY_FILE => 'Baskıya hazır dosyam var',
            self::ADAPT_DESIGN => 'Hazır tasarım markama uyarlansın',
            self::FROM_SCRATCH => 'Sıfırdan tasarım istiyorum',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function default(): self
    {
        return self::FROM_SCRATCH;
    }
}
