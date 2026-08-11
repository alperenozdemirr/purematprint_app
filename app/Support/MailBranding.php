<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Str;

class MailBranding
{
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

    public static function logoUrl(): string
    {
        $url = Setting::current()->logoUrl();

        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        return url($url);
    }
}
