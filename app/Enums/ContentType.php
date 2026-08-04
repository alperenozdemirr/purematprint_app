<?php
namespace App\Enums;

enum ContentType: string
{
    case PRODUCT = 'product';
    case USER = 'user';
    case OTHER = 'other'; //banner , sliders vb
    case BANNER = 'banner';
    case COLLECTION = 'collection';
    case BLOG = 'blog';
    case COMMENT = 'comment';
    case ORDER_FILE = 'order_file';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
