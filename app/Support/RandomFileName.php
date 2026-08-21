<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Str;

final class RandomFileName
{
    public static function generate(?string $extension = null, int $minLength = 6, int $maxLength = 10): string
    {
        $extension = self::normalizeExtension($extension);
        $length = random_int(min($minLength, $maxLength), max($minLength, $maxLength));
        $stem = Str::lower(Str::random($length));

        return $extension !== '' ? "{$stem}.{$extension}" : $stem;
    }

    private static function normalizeExtension(?string $extension): string
    {
        $extension = strtolower(trim((string) $extension, ".\t\n\r \0\x0B"));
        $extension = preg_replace('/[^a-z0-9]/', '', $extension) ?? '';

        return substr($extension, 0, 10);
    }
}
