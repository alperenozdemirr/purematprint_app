<?php
namespace App\Enums;

enum FileType: string
{
    case FILE = 'file';
    case IMAGE = 'image';



    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
