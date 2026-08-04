<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContentType;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'code',
        'price',
        'description',
        'stock_count',
        'shipping_weight',
        'shipping_length',
        'shipping_width',
        'shipping_height',
        'category_id',
        'status',
        'featured_status',
        'introduction_status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'shipping_weight' => 'decimal:3',
        'shipping_length' => 'integer',
        'shipping_width' => 'integer',
        'shipping_height' => 'integer',
        'featured_status' => 'boolean',
        'introduction_status' => 'boolean',
        'status' => Status::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_products');
    }

    public function images(): HasMany
    {
        return $this->hasMany(File::class, 'key_id')
            ->where('content_type', ContentType::PRODUCT->value)
            ->orderBy('number');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
