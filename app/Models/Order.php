<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'total',
        'subtotal',
        'is_discount_applied',
        'discount_slice',
        'shipping_is_free',
        'shipping_price',
        'address_id',
        'invoice_address_id',
        'note',
        'status',
        'invoice_status',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'shipping_price' => 'decimal:2',
        'is_discount_applied' => 'boolean',
        'shipping_is_free' => 'boolean',
        'invoice_status' => 'boolean',
        'status' => OrderStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function invoiceAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'invoice_address_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public static function generateCode(): string
    {
        $code = 'ORD-' . now()->format('Ymd');

        if (! static::query()->where('code', $code)->exists()) {
            return $code;
        }

        do {
            $code = 'ORD-' . now()->format('Ymd') . random_int(1000, 9999);
        } while (static::query()->where('code', $code)->exists());

        return $code;
    }
}
