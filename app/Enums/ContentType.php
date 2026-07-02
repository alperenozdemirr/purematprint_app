<?php
namespace App\Enums;

enum ContentType: string
{
    case PRODUCT = 'product';
    case USER = 'user';
    case OTHER = 'other'; //banner , sliders vb



    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
