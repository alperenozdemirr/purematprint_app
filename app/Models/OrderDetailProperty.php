<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetailProperty extends Model
{
    protected $fillable = [
        'order_detail_id',
        'group_title',
        'property_title',
        'price',
        'property_item_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function orderDetail(): BelongsTo
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function propertyItem(): BelongsTo
    {
        return $this->belongsTo(ProductPropertyItem::class, 'property_item_id');
    }
}
