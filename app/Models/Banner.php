<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_id',
        'redirect_url',
        'btn_title',
        'number',
    ];

    protected function buttonLabel(): Attribute
    {
        return Attribute::get(fn (): string => filled($this->btn_title) ? (string) $this->btn_title : 'Keşfet');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_id');
    }
}
