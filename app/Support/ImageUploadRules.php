<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Güvenli görsel uzantıları ve boyut limitleri.
 * SVG bilinçli olarak dışarıda (XSS riski).
 */
final class ImageUploadRules
{
    /** Admin panel görselleri: 40MB (KB) */
    public const ADMIN_MAX_KB = 40960;

    /** Anasayfa giriş görseli: 100MB (KB) */
    public const INTRO_MAX_KB = 102400;

    /** Kullanıcı yorum görselleri: 8MB (KB) */
    public const COMMENT_MAX_KB = 8192;

    public const MIMES = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'avif'];

    public static function mimesRule(): string
    {
        return 'mimes:'.implode(',', self::MIMES);
    }

    /**
     * @return list<string>
     */
    public static function adminImageRules(bool $required = false): array
    {
        return array_values(array_filter([
            $required ? 'required' : 'nullable',
            'file',
            self::mimesRule(),
            'max:'.self::ADMIN_MAX_KB,
        ]));
    }

    /**
     * @return list<string>
     */
    public static function introImageRules(bool $required = false): array
    {
        return array_values(array_filter([
            $required ? 'required' : 'nullable',
            'file',
            self::mimesRule(),
            'max:'.self::INTRO_MAX_KB,
        ]));
    }

    /**
     * @return list<string>
     */
    public static function adminImageItemRules(): array
    {
        return [
            'file',
            self::mimesRule(),
            'max:'.self::ADMIN_MAX_KB,
        ];
    }

    /**
     * @return list<string>
     */
    public static function commentImageItemRules(): array
    {
        return [
            'file',
            self::mimesRule(),
            'max:'.self::COMMENT_MAX_KB,
        ];
    }

    public static function acceptAttribute(): string
    {
        return '.jpg,.jpeg,.png,.webp,.gif,.bmp,.avif,image/jpeg,image/png,image/webp,image/gif,image/bmp,image/avif';
    }

    public static function humanList(): string
    {
        return 'JPG, JPEG, PNG, WEBP, GIF, BMP, AVIF';
    }
}
