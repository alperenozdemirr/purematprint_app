<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DiscountType;
use App\Enums\InvoiceType;
use App\Enums\OrderDesignStatus;
use App\Enums\OrderDesignType;
use App\Enums\OrderSourceChannel;
use App\Enums\OrderStatus;
use App\Enums\ContentType;
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
        'discount_type',
        'discount_slice',
        'discount_amount',
        'shipping_is_free',
        'shipping_price',
        'address_id',
        'invoice_address_id',
        'invoice_type',
        'tc_no',
        'company_name',
        'tax_number',
        'note',
        'status',
        'design_status',
        'design_type',
        'source_channel',
        'invoice_status',
        'shipink_order_id',
        'shipink_shipment_id',
        'shipping_carrier',
        'shipment_created_at',
        'tracking_number',
        'tracking_url',
        'shipping_label_url',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'shipping_synced_at',
        'confirmation_email_sent_at',
        'admin_notification_sent_at',
        'shipped_email_sent_at',
        'shipped_email_shipment_id',
        'delivered_email_sent_at',
        'carrier_picked_up_at',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_type' => DiscountType::class,
        'invoice_type' => InvoiceType::class,
        'shipping_price' => 'decimal:2',
        'is_discount_applied' => 'boolean',
        'shipping_is_free' => 'boolean',
        'invoice_status' => 'boolean',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'shipping_synced_at' => 'datetime',
        'shipment_created_at' => 'datetime',
        'confirmation_email_sent_at' => 'datetime',
        'admin_notification_sent_at' => 'datetime',
        'shipped_email_sent_at' => 'datetime',
        'delivered_email_sent_at' => 'datetime',
        'carrier_picked_up_at' => 'datetime',
        'status' => OrderStatus::class,
        'design_status' => OrderDesignStatus::class,
        'design_type' => OrderDesignType::class,
        'source_channel' => OrderSourceChannel::class,
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

    public function orderFiles(): HasMany
    {
        return $this->hasMany(File::class, 'key_id')
            ->where('content_type', ContentType::ORDER_FILE->value)
            ->orderBy('number');
    }

    public function invoiceFile(): HasOne
    {
        return $this->hasOne(File::class, 'key_id')
            ->where('content_type', ContentType::ORDER_INVOICE->value)
            ->latestOfMany();
    }

    public function designFile(): HasOne
    {
        return $this->hasOne(File::class, 'key_id')
            ->where('content_type', ContentType::ORDER_DESIGN->value)
            ->latestOfMany();
    }

    public function designRequests(): HasMany
    {
        return $this->hasMany(OrderDesignRequest::class)->latest();
    }

    public function canManageOrderFilesAndDesign(): bool
    {
        return $this->status === OrderStatus::PREPARING;
    }

    public static function generateCode(): string
    {
        $year = now()->format('Y');

        do {
            $suffix = (string) random_int(100000, 999999);
            $code = $year.'-'.$suffix;
        } while (static::query()->where('code', $code)->exists());

        return $code;
    }

    public function discountLabel(): ?string
    {
        if (! $this->is_discount_applied) {
            return null;
        }

        if ($this->discount_type === DiscountType::PERCENT) {
            return '%'.number_format((float) $this->discount_slice, 0, ',', '.');
        }

        if ($this->discount_type === DiscountType::FIXED) {
            return number_format((float) ($this->discount_amount ?? 0), 0, ',', '.').' ₺';
        }

        return null;
    }

    public function isCorporateInvoice(): bool
    {
        return $this->invoice_type === InvoiceType::CORPORATE;
    }

    public function invoiceTypeLabel(): string
    {
        return $this->invoice_type?->label() ?? InvoiceType::INDIVIDUAL->label();
    }

    public function isDomesticShipment(): bool
    {
        return $this->address?->isDomestic() ?? false;
    }

    public function isInternationalShipment(): bool
    {
        return $this->address?->isInternational() ?? false;
    }

    public function hasShipinkShipment(): bool
    {
        return filled($this->shipink_shipment_id);
    }

    public function canCreateShipinkShipment(): bool
    {
        return $this->isDomesticShipment()
            && $this->status === OrderStatus::PREPARING
            && ! $this->hasShipinkShipment();
    }

    public function shippingCarrierLabel(): ?string
    {
        if (! filled($this->shipping_carrier)) {
            return null;
        }

        return match (strtolower((string) $this->shipping_carrier)) {
            'ptt' => 'PTT Kargo',
            'aras' => 'Aras Kargo',
            'hepsijet' => 'Hepsijet',
            'yurtici' => 'Yurtiçi Kargo',
            'mng' => 'MNG Kargo',
            'surat' => 'Sürat Kargo',
            'ups' => 'UPS',
            'fedex' => 'FedEx',
            'dhl' => 'DHL',
            default => ucfirst((string) $this->shipping_carrier).' Kargo',
        };
    }

    public function canCancelShipinkShipment(): bool
    {
        if (! $this->hasShipinkShipment() || $this->shipment_created_at === null) {
            return false;
        }

        if (in_array($this->status, [OrderStatus::COMPLETED, OrderStatus::CANCELLED], true)) {
            return false;
        }

        $minutes = (int) config('shipink.shipment_cancel_minutes', 60);

        return $this->shipment_created_at->copy()->addMinutes($minutes)->isFuture();
    }

    public function shipinkCancelDeadline(): ?\Illuminate\Support\Carbon
    {
        if ($this->shipment_created_at === null) {
            return null;
        }

        $minutes = (int) config('shipink.shipment_cancel_minutes', 60);

        return $this->shipment_created_at->copy()->addMinutes($minutes);
    }

    public function needsShipinkShipment(): bool
    {
        return $this->isDomesticShipment()
            && $this->status === OrderStatus::PREPARING
            && ! $this->hasShipinkShipment();
    }

    public function isShippingSyncStale(): bool
    {
        if (! $this->hasShipinkShipment()) {
            return false;
        }

        if (in_array($this->status, [OrderStatus::COMPLETED, OrderStatus::CANCELLED], true)) {
            return false;
        }

        $hours = max(1, (int) config('shipink.stale_sync_hours', 6));
        $threshold = now()->subHours($hours);
        $reference = $this->shipping_synced_at ?? $this->shipment_created_at;

        return $reference !== null && $reference->lt($threshold);
    }

    public function canBeCancelledByAdmin(): bool
    {
        return ! in_array($this->status, [OrderStatus::COMPLETED, OrderStatus::CANCELLED], true);
    }
}
