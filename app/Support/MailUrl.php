<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class MailUrl
{
    public static function root(): string
    {
        return rtrim((string) config('mail.app_url', config('app.url')), '/');
    }

    public static function apply(): void
    {
        $root = self::root();

        if ($root === '') {
            return;
        }

        URL::forceRootUrl($root);

        if (Str::startsWith($root, 'https://')) {
            URL::forceScheme('https');
        }
    }

    public static function route(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        self::apply();

        return route($name, $parameters, $absolute);
    }

    public static function to(string $path): string
    {
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return self::rewriteHost($path);
        }

        return self::root().'/'.ltrim($path, '/');
    }

    public static function rewriteHost(string $url): string
    {
        $parts = parse_url($url);

        if ($parts === false || empty($parts['path'])) {
            return $url;
        }

        $path = $parts['path'];

        if (! empty($parts['query'])) {
            $path .= '?'.$parts['query'];
        }

        if (! empty($parts['fragment'])) {
            $path .= '#'.$parts['fragment'];
        }

        return self::root().$path;
    }
}
