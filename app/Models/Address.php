<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AddressScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scope',
        'title',
        'content',
        'city_id',
        'county_id',
        'country',
        'state',
        'city_name',
        'postal_code',
    ];

    protected $casts = [
        'scope' => AddressScope::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }

    public function shippingOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'address_id');
    }

    public function invoiceOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'invoice_address_id');
    }

    public function isLinkedToOrder(): bool
    {
        return Order::query()
            ->where(function ($query): void {
                $query->where('address_id', $this->id)
                    ->orWhere('invoice_address_id', $this->id);
            })
            ->exists();
    }

    public function isDomestic(): bool
    {
        return $this->scope === AddressScope::DOMESTIC;
    }

    public function isInternational(): bool
    {
        return $this->scope === AddressScope::INTERNATIONAL;
    }

    public function formattedLocation(): string
    {
        if ($this->isInternational()) {
            return collect([
                $this->city_name,
                $this->state,
                $this->postal_code,
                $this->country,
            ])->filter()->implode(', ');
        }

        return collect([
            $this->county?->name,
            $this->city?->name,
            $this->postal_code,
        ])->filter()->implode(', ');
    }

    protected function scopeLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->scope?->label() ?? AddressScope::DOMESTIC->label());
    }
}
