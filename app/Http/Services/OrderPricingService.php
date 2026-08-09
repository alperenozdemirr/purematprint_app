<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\DiscountScope;
use App\Enums\DiscountType;
use App\Enums\ShippingMode;
use App\Models\Order;
use App\Models\Setting;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class OrderPricingService
{
    public function __construct(protected ProductPropertySelectionService $propertySelection)
    {
    }

    public function calculate(iterable $cartItems, ?User $user = null): array
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

        $shipping = $this->calculateShipping($settings, $subtotal, $totalQty > 0);
        $total = $discountedSubtotal + $shipping['shippingCost'];

        return [
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
        ];
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
    private function calculateShipping(Setting $settings, float $subtotal, bool $hasItems): array
    {
        if (! $hasItems) {
            return [
                'shippingFree' => true,
                'shippingCost' => 0.0,
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
