<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MailBranding
{
    /** User theme — resources/views/user/layout.blade.php (announce) */
    public const COLOR_ANNOUNCE = '#b61d0f';

    public const COLOR_ANNOUNCE_DARK = '#96180c';

    public const COLOR_BG = '#faf6ee';

    public const COLOR_SURFACE = '#fffdf8';

    public const COLOR_CREAM = '#fbf8f1';

    public const COLOR_INK = '#1a1a1a';

    public const COLOR_MUTED = '#5e5a54';

    public const COLOR_ACTION = '#5a544e';

    public const COLOR_ACTION_HOVER = '#6b645c';

    public const COLOR_ON_DARK = '#faf6ee';

    public const COLOR_DARK = '#2a2826';

    public const FONT_BODY = 'IBM Plex Sans, Arial, sans-serif';

    public const FONT_HEADING = 'Georgia, Times New Roman, serif';

    /** @var list<string> */
    private const DEFAULT_LOGO_CANDIDATES = [
        'shared_directory/logo-light.png',
        'shared_directory/logo-white.png',
        'shared_directory/logo.png',
        'shared_directory/logo.jpg',
        'shared_directory/logo.jpeg',
        'shared_directory/logo.avif',
    ];

    public static function logoUrl(): string
    {
        $setting = Setting::current()->loadMissing('logo');

        if ($setting->hasCustomLogo() && $setting->logo) {
            return self::emailSafeImageUrl(self::absoluteUrl($setting->logo->url));
        }

        return self::emailSafeImageUrl(self::absoluteUrl(self::resolveDefaultLogoPath()));
    }

    private static function resolveDefaultLogoPath(): string
    {
        foreach (self::DEFAULT_LOGO_CANDIDATES as $path) {
            if (self::pathExists($path)) {
                return self::mediaPathUrl($path);
            }
        }

        return self::mediaPathUrl(Setting::DEFAULT_LOGO);
    }

    private static function pathExists(string $path): bool
    {
        try {
            if (Storage::disk('r2')->exists($path)) {
                return true;
            }
        } catch (\Throwable) {
            // R2 may be unavailable in local/dev.
        }

        return is_file(public_path($path));
    }

    private static function mediaPathUrl(string $path): string
    {
        if (is_file(public_path($path))) {
            return asset($path);
        }

        return route('media.show', ['path' => $path], absolute: true);
    }

    private static function absoluteUrl(string $url): string
    {
        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        $root = rtrim((string) (config('app.asset_url') ?: config('app.url')), '/');

        if (Str::startsWith($url, '/')) {
            return $root.$url;
        }

        return $root.'/'.ltrim($url, '/');
    }

    private static function emailSafeImageUrl(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (! in_array($extension, ['avif', 'webp'], true)) {
            return $url;
        }

        $relativePath = self::relativeMediaPath($path);

        foreach (['png', 'jpg', 'jpeg'] as $altExtension) {
            $altRelativePath = preg_replace('/\.[^.]+$/', '.'.$altExtension, $relativePath) ?? $relativePath;

            if ($altRelativePath !== $relativePath && self::pathExists($altRelativePath)) {
                return self::absoluteUrl(self::mediaPathUrl($altRelativePath));
            }
        }

        return $url;
    }

    private static function relativeMediaPath(string $path): string
    {
        if (Str::contains($path, '/media/')) {
            return ltrim(Str::after($path, '/media/'), '/');
        }

        return ltrim($path, '/');
    }
}
