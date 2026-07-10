<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContentType;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'category_id',
        'status',
        'featured_status',
        'introduction_status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'featured_status' => 'boolean',
        'introduction_status' => 'boolean',
        'status' => Status::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(File::class, 'key_id')
            ->where('content_type', ContentType::PRODUCT->value)
            ->orderBy('number');
    }
}
