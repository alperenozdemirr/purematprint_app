<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'user_id',
        'key_id',
        'file_type',
        'content_type',
        'number',
    ];

    public function getUrlAttribute(): string
    {
        $directory = match ($this->content_type) {
            ContentType::PRODUCT->value => 'shared_directory/images/products',
            ContentType::BANNER->value => 'shared_directory/images/banners',
            ContentType::USER->value => 'shared_directory/images/users',
            default => 'shared_directory/images/other',
        };

        return asset($directory . '/' . $this->file_name);
    }
}
