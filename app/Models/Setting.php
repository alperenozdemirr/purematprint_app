<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DiscountScope;
use App\Enums\DiscountType;
use App\Enums\ShippingMode;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public const SINGLETON_ID = 1;

    protected $fillable = [
        'site_open',
        'discount_enabled',
        'discount_type',
        'discount_value',
        'discount_scope',
        'shipping_mode',
        'shipping_fee',
        'shipping_free_limit_enabled',
        'shipping_free_limit',
    ];

    protected $casts = [
        'site_open' => 'boolean',
        'discount_enabled' => 'boolean',
        'discount_value' => 'decimal:2',
        'discount_type' => DiscountType::class,
        'discount_scope' => DiscountScope::class,
        'shipping_mode' => ShippingMode::class,
        'shipping_fee' => 'decimal:2',
        'shipping_free_limit_enabled' => 'boolean',
        'shipping_free_limit' => 'decimal:2',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => self::SINGLETON_ID],
            [
                'site_open' => true,
                'discount_enabled' => false,
                'discount_type' => DiscountType::PERCENT,
                'discount_value' => 0,
                'discount_scope' => DiscountScope::ALL_ORDERS,
                'shipping_mode' => ShippingMode::PAID,
                'shipping_fee' => 49,
                'shipping_free_limit_enabled' => true,
                'shipping_free_limit' => 500,
            ]
        );
    }

    public static function saveSingleton(array $attributes): self
    {
        return static::query()->updateOrCreate(
            ['id' => self::SINGLETON_ID],
            $attributes
        );
    }

    public function shippingPromoText(): ?string
    {
        if ($this->shipping_mode === ShippingMode::FREE) {
            return 'Tüm siparişlerde ücretsiz kargo';
        }

        if (
            $this->shipping_free_limit_enabled
            && $this->shipping_free_limit !== null
            && (float) $this->shipping_free_limit > 0
        ) {
            return number_format((float) $this->shipping_free_limit, 0, ',', '.').'₺ üzeri ücretsiz kargo';
        }

        return null;
    }

    public function shippingPromoSentence(): ?string
    {
        if ($this->shipping_mode === ShippingMode::FREE) {
            return 'Tüm siparişlerinizde ücretsiz kargo avantajından yararlanabilirsiniz.';
        }

        if (
            $this->shipping_free_limit_enabled
            && $this->shipping_free_limit !== null
            && (float) $this->shipping_free_limit > 0
        ) {
            return number_format((float) $this->shipping_free_limit, 0, ',', '.').'₺ ve üzeri siparişlerinizde ücretsiz kargo avantajından yararlanabilirsiniz.';
        }

        return null;
    }
}
