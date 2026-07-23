<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DiscountScope;
use App\Enums\DiscountType;
use App\Enums\ShippingMode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    public const SINGLETON_ID = 1;

    public const DEFAULT_LOGO = 'shared_directory/logo.avif';

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
        'email',
        'address',
        'mobile_phone',
        'business_phone',
        'instagram_url',
        'twitter_url',
        'facebook_url',
        'whatsapp_phone',
        'short_info',
        'logo_id',
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
        'logo_id' => 'integer',
    ];

    public function logo(): BelongsTo
    {
        return $this->belongsTo(File::class, 'logo_id');
    }

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
                'whatsapp_phone' => '905321234567',
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

    public function logoUrl(): string
    {
        return $this->logo?->url ?? asset(self::DEFAULT_LOGO);
    }

    public function hasCustomLogo(): bool
    {
        if ($this->logo_id === null) {
            return false;
        }

        if ($this->relationLoaded('logo')) {
            return $this->logo !== null;
        }

        return $this->logo()->exists();
    }

    public function whatsappDigits(): ?string
    {
        if (! $this->whatsapp_phone) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $this->whatsapp_phone);

        return $digits !== '' ? $digits : null;
    }

    public function whatsappLink(?string $text = null): ?string
    {
        $digits = $this->whatsappDigits();

        if (! $digits) {
            return null;
        }

        $url = 'https://wa.me/'.$digits;

        if ($text !== null && $text !== '') {
            $url .= '?text='.rawurlencode($text);
        }

        return $url;
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

    public function shippingDetailText(): string
    {
        if ($this->shipping_mode === ShippingMode::FREE) {
            return 'Tüm siparişlerde ücretsiz kargo uygulanır.';
        }

        $fee = number_format((float) $this->shipping_fee, 0, ',', '.').'₺';

        if (
            $this->shipping_free_limit_enabled
            && $this->shipping_free_limit !== null
            && (float) $this->shipping_free_limit > 0
        ) {
            $limit = number_format((float) $this->shipping_free_limit, 0, ',', '.').'₺';

            return "Standart siparişler 3–5 iş günü içinde kargoya verilir. {$limit} üzeri siparişlerde kargo ücretsizdir; altında {$fee} kargo ücreti uygulanır.";
        }

        return "Standart siparişler 3–5 iş günü içinde kargoya verilir. Tüm siparişlerde {$fee} kargo ücreti uygulanır.";
    }
}
