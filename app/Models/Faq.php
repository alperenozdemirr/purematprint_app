<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model
{
    protected $fillable = [
        'group_id',
        'title',
        'content',
        'number',
        'fixed_status',
    ];

    protected $casts = [
        'fixed_status' => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(FaqGroup::class, 'group_id');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderByRaw('number IS NULL, number ASC')
            ->orderBy('title');
    }

    public function scopeFixed(Builder $query): Builder
    {
        return $query->where('fixed_status', true);
    }
}
