<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\ContentType;

class MediaPath
{
    public const USER = 'shared_directory/images/users';

    public const PRODUCT = 'shared_directory/images/products';

    public const BANNER = 'shared_directory/images/banners';

    public const COLLECTION = 'shared_directory/images/collections';

    public const BLOG = 'shared_directory/images/blogs';

    public const COMMENT = 'shared_directory/images/comments';

    public const ORDER_FILE = 'shared_directory/files/order_files';

    public const ORDER_INVOICE = 'shared_directory/files/order_invoices';

    public const OTHER = 'shared_directory/images/other';

    public const ALLOWED_PREFIX = 'shared_directory/';

    public static function forContentType(ContentType|string|null $contentType): string
    {
        $value = $contentType instanceof ContentType ? $contentType->value : (string) $contentType;

        return match ($value) {
            ContentType::PRODUCT->value => self::PRODUCT,
            ContentType::BANNER->value => self::BANNER,
            ContentType::COLLECTION->value => self::COLLECTION,
            ContentType::BLOG->value => self::BLOG,
            ContentType::COMMENT->value => self::COMMENT,
            ContentType::ORDER_FILE->value => self::ORDER_FILE,
            ContentType::ORDER_INVOICE->value => self::ORDER_INVOICE,
            ContentType::USER->value => self::USER,
            default => self::OTHER,
        };
    }

    public static function relativePath(ContentType|string|null $contentType, string $fileName): string
    {
        return self::forContentType($contentType).'/'.$fileName;
    }

    public static function url(ContentType|string|null $contentType, string $fileName): string
    {
        return route('media.show', ['path' => self::relativePath($contentType, $fileName)]);
    }

    public static function isAllowed(string $path): bool
    {
        if ($path === '' || str_starts_with($path, '/') || str_contains($path, '..')) {
            return false;
        }

        return str_starts_with($path, self::ALLOWED_PREFIX);
    }
}
