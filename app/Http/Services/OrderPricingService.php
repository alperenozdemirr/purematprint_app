<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\DiscountScope;
use App\Enums\DiscountType;
use App\Enums\ShippingMode;
use App\Http\Services\Exceptions\ExchangeRateUnavailableException;
use App\Models\Address;
use App\Models\Order;
use App\Models\Setting;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class OrderPricingService
{
    public function __construct(
        protected ProductPropertySelectionService $propertySelection,
        protected CurrencyConversionService $currencyConversion,
    ) {
    }

    public function calculate(iterable $cartItems, ?User $user = null, ?Address $address = null): array
    {
        $settings = Setting::current();

        $subtotal = 0.0;
        $totalQty = 0;

        foreach ($cartItems as $item) {
            /** @var ShoppingCart $item */
            $unitPrice = $this->unitPriceForCartItem($item);
            $subtotal += $unitPrice * (int) $item->quantity;
            $totalQty += (int) $item->quantity;
        }

        $discountApplied = false;
        $discountType = null;
        $discountValue = null;
        $discountAmount = 0.0;

        if ($settings->discount_enabled && $settings->discount_type && $settings->discount_value > 0) {
            $isEligible = $this->isDiscountEligible($settings, $user);

            if ($isEligible) {
                $discountApplied = true;
                $discountType = $settings->discount_type;
                $discountValue = (float) $settings->discount_value;

                $discountAmount = $discountType === DiscountType::PERCENT
                    ? round($subtotal * $discountValue / 100, 2)
                    : min($subtotal, $discountValue);
            }
        }

        $discountedSubtotal = max(0, $subtotal - $discountAmount);

        $isInternational = $address?->isInternational() ?? false;

        $shipping = $this->calculateShipping(
            $settings,
            $subtotal,
            $totalQty > 0,
            $isInternational,
            $user,
        );

        $total = $discountedSubtotal + $shipping['shippingCost'];

        $result = [
            'subtotal' => $subtotal,
            'discountApplied' => $discountApplied,
            'discountType' => $discountType?->value,
            'discountValue' => $discountValue,
            'discountAmount' => $discountAmount,
            'discountedSubtotal' => $discountedSubtotal,
            'shippingFree' => $shipping['shippingFree'],
            'shippingCost' => $shipping['shippingCost'],
            'shippingRemaining' => $shipping['shippingRemaining'],
            'totalQty' => $totalQty,
            'total' => $total,
            'isInternational' => $isInternational,
        ];

        if ($isInternational) {
            try {
                $fxRate = $this->currencyConversion->eurPerTry();

                $result['chargeCurrency'] = 'EUR';
                $result['fxRate'] = $fxRate;
                $result['eurToTry'] = $this->currencyConversion->eurToTry();
                $result['chargeSubtotal'] = $this->currencyConversion->tryToEur($discountedSubtotal);
                $result['chargeShippingCost'] = $this->currencyConversion->tryToEur($shipping['shippingCost']);
                $result['chargeTotal'] = $this->currencyConversion->quoteCartEurTotal(
                    $cartItems,
                    $result,
                    fn ($item) => $this->unitPriceForCartItem($item),
                );
            } catch (ExchangeRateUnavailableException) {
                $result['exchangeRateUnavailable'] = true;
            }
        }

        return $result;
    }

    public function unitPriceForCartItem(ShoppingCart $item): float
    {
        try {
            return $this->propertySelection->unitPriceForCartItem($item);
        } catch (ValidationException) {
            return round((float) ($item->product?->price ?? 0), 2);
        }
    }

    private function isDiscountEligible(Setting $settings, ?User $user): bool
    {
        if ($settings->discount_scope === DiscountScope::ALL_ORDERS) {
            return true;
        }

        if ($user === null) {
            return false;
        }

        return ! Order::query()->where('user_id', $user->id)->exists();
    }

    /**
     * @return array{shippingFree: bool, shippingCost: float, shippingRemaining: float}
     */
    private function calculateShipping(
        Setting $settings,
        float $subtotal,
        bool $hasItems,
        bool $isInternational,
        ?User $user,
    ): array {
        if (! $hasItems) {
            return [
                'shippingFree' => true,
                'shippingCost' => 0.0,
                'shippingRemaining' => 0.0,
            ];
        }

        if ($settings->shipping_first_order_free && $user !== null) {
            $hasPreviousOrder = Order::query()->where('user_id', $user->id)->exists();

            if (! $hasPreviousOrder) {
                return [
                    'shippingFree' => true,
                    'shippingCost' => 0.0,
                    'shippingRemaining' => 0.0,
                ];
            }
        }

        if ($isInternational) {
            if ($settings->international_shipping_mode === ShippingMode::FREE) {
                return [
                    'shippingFree' => true,
                    'shippingCost' => 0.0,
                    'shippingRemaining' => 0.0,
                ];
            }

            return [
                'shippingFree' => false,
                'shippingCost' => (float) $settings->international_shipping_fee,
                'shippingRemaining' => 0.0,
            ];
        }

        if ($settings->shipping_mode === ShippingMode::FREE) {
            return [
                'shippingFree' => true,
                'shippingCost' => 0.0,
                'shippingRemaining' => 0.0,
            ];
        }

        if (
            $settings->shipping_free_limit_enabled
            && $settings->shipping_free_limit !== null
            && $subtotal >= (float) $settings->shipping_free_limit
        ) {
            return [
                'shippingFree' => true,
                'shippingCost' => 0.0,
                'shippingRemaining' => 0.0,
            ];
        }

        $shippingRemaining = 0.0;

        if ($settings->shipping_free_limit_enabled && $settings->shipping_free_limit !== null) {
            $shippingRemaining = max(0, (float) $settings->shipping_free_limit - $subtotal);
        }

        return [
            'shippingFree' => false,
            'shippingCost' => (float) $settings->shipping_fee,
            'shippingRemaining' => $shippingRemaining,
        ];
    }
}
