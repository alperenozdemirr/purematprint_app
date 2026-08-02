<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\MediaPath;
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
        return MediaPath::url($this->content_type, $this->file_name);
    }

    public function storagePath(): string
    {
        return MediaPath::relativePath($this->content_type, $this->file_name);
    }
}
