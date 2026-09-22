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
        return self::ADAPT_DESIGN;
    }

    /**
     * Ödeme öncesi işlem adımında sunulan seçenekler.
     *
     * @return list<self>
     */
    public static function checkoutOptions(): array
    {
        return [
            self::ADAPT_DESIGN,
            self::READY_FILE,
        ];
    }

    /**
     * @return list<string>
     */
    public static function checkoutOptionValues(): array
    {
        return array_map(
            fn (self $type): string => $type->value,
            self::checkoutOptions(),
        );
    }
}
