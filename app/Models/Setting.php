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

    public const DEFAULT_SPOTLIGHT_TITLE = 'İşlevsel, minimal ve oyunbaz baskı ürünleri';

    public const DEFAULT_SPOTLIGHT_SUBTITLE = 'DESIGN';

    public const DEFAULT_SPOTLIGHT_IMAGE = 'user/assets/foto1.jpeg';

    public const DEFAULT_BAND_IMAGE = 'user/assets/foto2.jpeg';

    public const DEFAULT_TEAM_NOTE_TITLE = "PureMatPrint'ten Bir Not";

    public const DEFAULT_TEAM_NOTE_DESCRIPTION = "Yaratıcı mekanlar için sade baskı ürünleri üretmek amacıyla kurulduk. Bugün hâlâ aynı tutkuyla çalışıyor, sizin gibi markaların mekanlarını yükseltmelerine yardımcı oluyoruz. Sorularınız olursa bizimle iletişime geçmekten çekinmeyin.\n\nKeyifli projeler,";

    public const DEFAULT_TEAM_NOTE_IMAGE = 'user/assets/foto2.jpeg';

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
        'shipping_duration_text',
        'delivery_time_text',
        'email',
        'order_notification_emails',
        'address',
        'mobile_phone',
        'business_phone',
        'instagram_url',
        'twitter_url',
        'facebook_url',
        'whatsapp_phone',
        'short_info',
        'show_real_homepage_reviews',
        'logo_id',
        'spotlight_title',
        'spotlight_subtitle',
        'spotlight_image_id',
        'band_image_id',
        'team_note_title',
        'team_note_description',
        'team_note_image_id',
        'shipink_warehouse_id',
        'shipink_warehouse_name',
        'shipink_carrier_account_id',
        'shipink_carrier_account_label',
        'shipink_carrier_provider',
        'shipink_carrier_service_id',
        'shipink_card_id',
        'shipink_card_label',
        'shipink_default_weight',
        'shipink_default_length',
        'shipink_default_width',
        'shipink_default_height',
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
        'show_real_homepage_reviews' => 'boolean',
        'logo_id' => 'integer',
        'spotlight_image_id' => 'integer',
        'band_image_id' => 'integer',
        'team_note_image_id' => 'integer',
        'shipink_default_weight' => 'integer',
        'shipink_default_length' => 'integer',
        'shipink_default_width' => 'integer',
        'shipink_default_height' => 'integer',
        'order_notification_emails' => 'array',
    ];

    public function logo(): BelongsTo
    {
        return $this->belongsTo(File::class, 'logo_id');
    }

    public function spotlightImage(): BelongsTo
    {
        return $this->belongsTo(File::class, 'spotlight_image_id');
    }

    public function bandImage(): BelongsTo
    {
        return $this->belongsTo(File::class, 'band_image_id');
    }

    public function teamNoteImage(): BelongsTo
    {
        return $this->belongsTo(File::class, 'team_note_image_id');
    }

    public function spotlightTitleLabel(): string
    {
        return filled($this->spotlight_title)
            ? (string) $this->spotlight_title
            : self::DEFAULT_SPOTLIGHT_TITLE;
    }

    public function spotlightSubtitleLabel(): string
    {
        return filled($this->spotlight_subtitle)
            ? (string) $this->spotlight_subtitle
            : self::DEFAULT_SPOTLIGHT_SUBTITLE;
    }

    public function spotlightImageUrl(): string
    {
        return $this->spotlightImage?->url ?? asset(self::DEFAULT_SPOTLIGHT_IMAGE);
    }

    public function bandImageUrl(): string
    {
        return $this->bandImage?->url ?? asset(self::DEFAULT_BAND_IMAGE);
    }

    public function teamNoteTitleLabel(): string
    {
        return filled($this->team_note_title)
            ? (string) $this->team_note_title
            : self::DEFAULT_TEAM_NOTE_TITLE;
    }

    /**
     * @return list<string>
     */
    public function teamNoteDescriptionParagraphs(): array
    {
        $text = filled($this->team_note_description)
            ? (string) $this->team_note_description
            : self::DEFAULT_TEAM_NOTE_DESCRIPTION;

        $paragraphs = preg_split("/\r\n|\n|\r/", $text) ?: [];

        return collect($paragraphs)
            ->map(fn (string $paragraph) => trim($paragraph))
            ->filter()
            ->values()
            ->all();
    }

    public function teamNoteImageUrl(): string
    {
        return $this->teamNoteImage?->url ?? asset(self::DEFAULT_TEAM_NOTE_IMAGE);
    }

    public function hasCustomSpotlightImage(): bool
    {
        return $this->spotlight_image_id !== null && $this->spotlightImage !== null;
    }

    public function hasCustomBandImage(): bool
    {
        return $this->band_image_id !== null && $this->bandImage !== null;
    }

    public function hasCustomTeamNoteImage(): bool
    {
        return $this->team_note_image_id !== null && $this->teamNoteImage !== null;
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
                'show_real_homepage_reviews' => false,
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

    public function deliveryTimeLabel(): string
    {
        return filled($this->delivery_time_text)
            ? (string) $this->delivery_time_text
            : '1–5 Gün';
    }

    public function shippingDurationLabel(): string
    {
        return filled($this->shipping_duration_text)
            ? (string) $this->shipping_duration_text
            : '3–5 Gün';
    }

    /**
     * @return array{title: string, subtitle: string}
     */
    public function shippingTrustBadge(): array
    {
        if ($this->shipping_mode === ShippingMode::FREE) {
            return [
                'title' => 'Ücretsiz',
                'subtitle' => 'Kargo',
            ];
        }

        if (
            $this->shipping_free_limit_enabled
            && $this->shipping_free_limit !== null
            && (float) $this->shipping_free_limit > 0
        ) {
            return [
                'title' => number_format((float) $this->shipping_free_limit, 0, ',', '.').'₺+',
                'subtitle' => 'Ücretsiz Kargo',
            ];
        }

        return [
            'title' => number_format((float) $this->shipping_fee, 0, ',', '.').'₺',
            'subtitle' => 'Kargo Ücreti',
        ];
    }

    public function shippingDetailText(): string
    {
        $duration = $this->shippingDurationLabel();

        if ($this->shipping_mode === ShippingMode::FREE) {
            return "Tüm siparişlerde ücretsiz kargo uygulanır. Standart siparişler {$duration} içinde kargoya verilir.";
        }

        $fee = number_format((float) $this->shipping_fee, 0, ',', '.').'₺';

        if (
            $this->shipping_free_limit_enabled
            && $this->shipping_free_limit !== null
            && (float) $this->shipping_free_limit > 0
        ) {
            $limit = number_format((float) $this->shipping_free_limit, 0, ',', '.').'₺';

            return "Standart siparişler {$duration} içinde kargoya verilir. {$limit} üzeri siparişlerde kargo ücretsizdir; altında {$fee} kargo ücreti uygulanır.";
        }

        return "Standart siparişler {$duration} içinde kargoya verilir. Tüm siparişlerde {$fee} kargo ücreti uygulanır.";
    }

    /**
     * @return list<string>
     */
    public function orderNotificationEmails(): array
    {
        $emails = is_array($this->order_notification_emails) ? $this->order_notification_emails : [];

        return collect($emails)
            ->map(fn ($email) => strtolower(trim((string) $email)))
            ->filter(fn (string $email) => filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
            ->unique()
            ->take(4)
            ->values()
            ->all();
    }
}
