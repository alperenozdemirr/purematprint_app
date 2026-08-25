<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'paid_amount',
        'installment_count',
        'paid_currency',
        'paid_amount_foreign',
        'foreign_currency',
        'fx_rate',
        'status',
        'provider',
        'provider_payment_id',
        'provider_token',
        'refunded_at',
    ];

    protected $casts = [
        'paid_amount' => 'decimal:2',
        'paid_amount_foreign' => 'decimal:2',
        'fx_rate' => 'decimal:6',
        'status' => PaymentStatus::class,
        'provider' => PaymentProvider::class,
        'refunded_at' => 'datetime',
        'installment_count' => 'integer',
    ];

    public function usesInstallments(): bool
    {
        return $this->installment_count !== null && (int) $this->installment_count > 1;
    }

    public function paidAmountDiffersFromOrder(): bool
    {
        $order = $this->relationLoaded('order') ? $this->order : $this->order()->first();

        if ($order === null) {
            return false;
        }

        return abs((float) $this->paid_amount - (float) $order->total) >= 0.01;
    }

    public function formattedPaidAmount(): string
    {
        return number_format((float) $this->paid_amount, 2, ',', '.').' ₺';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
