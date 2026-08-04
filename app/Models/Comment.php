<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'content',
        'order_detail_id',
        'rating',
        'is_visible',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'is_visible' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetail(): BelongsTo
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(File::class, 'key_id')
            ->where('content_type', ContentType::COMMENT->value)
            ->orderBy('number');
    }
}
