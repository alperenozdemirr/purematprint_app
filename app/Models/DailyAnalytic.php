<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'total_orders',
        'paid_orders',
        'cancelled_orders',
        'refunded_orders',
        'completed_orders',
        'gross_revenue',
        'net_revenue',
        'discount_total',
        'shipping_revenue',
        'average_order_value',
        'new_customers',
        'returning_customers',
        'new_registrations',
        'products_sold_quantity',
        'new_products',
        'order_files_uploaded',
        'comments_created',
        'domestic_orders',
        'international_orders',
    ];

    protected $casts = [
        'date' => 'date',
        'gross_revenue' => 'decimal:2',
        'net_revenue' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'shipping_revenue' => 'decimal:2',
        'average_order_value' => 'decimal:2',
    ];
}
