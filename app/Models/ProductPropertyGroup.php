<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProductPropertyGroupType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductPropertyGroup extends Model
{
    protected $fillable = [
        'product_id',
        'title',
        'type',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'type' => ProductPropertyGroupType::class,
        'is_required' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductPropertyItem::class, 'group_id')->orderBy('sort_order')->orderBy('id');
    }
}
