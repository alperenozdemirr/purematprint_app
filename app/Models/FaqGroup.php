<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FaqGroup extends Model
{
    protected $fillable = [
        'title',
        'number',
    ];

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class, 'group_id');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderByRaw('number IS NULL, number ASC')
            ->orderBy('title');
    }
}
