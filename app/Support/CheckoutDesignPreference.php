<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\OrderDesignType;
use App\Models\ShoppingCart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

final class CheckoutDesignPreference
{
    public const SESSION_KEY = 'checkout.design_type';

    public static function get(): string
    {
        $value = Session::get(self::SESSION_KEY);

        if (is_string($value) && in_array($value, OrderDesignType::checkoutOptionValues(), true)) {
            return $value;
        }

        return OrderDesignType::default()->value;
    }

    public static function set(?string $value): void
    {
        if ($value === null || ! in_array($value, OrderDesignType::checkoutOptionValues(), true)) {
            return;
        }

        Session::put(self::SESSION_KEY, $value);
    }

    /**
     * @param  Collection<int, ShoppingCart>|iterable<ShoppingCart>  $cartItems
     */
    public static function cartShowsDesignPreference(iterable $cartItems): bool
    {
        foreach ($cartItems as $item) {
            $product = $item->product;

            if ($product !== null && $product->showsDesignPreference()) {
                return true;
            }
        }

        return false;
    }
}
