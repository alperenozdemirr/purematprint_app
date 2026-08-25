<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomepageDemoReview extends Model
{
    protected $fillable = [
        'quote',
        'author',
        'stars',
        'image_id',
        'sort_order',
        'is_visible',
    ];

    protected $casts = [
        'stars' => 'integer',
        'sort_order' => 'integer',
        'is_visible' => 'boolean',
        'image_id' => 'integer',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_id');
    }

    public function imageUrl(): string
    {
        return $this->image?->url ?? asset('user/assets/foto5.jpeg');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public static function visibleOrdered(): Builder
    {
        return static::query()->ordered()->visible();
    }
}
