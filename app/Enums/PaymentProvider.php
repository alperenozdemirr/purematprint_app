<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentProvider: string
{
    case IYZICO = 'iyzico';
    case STRIPE = 'stripe';

    public function label(): string
    {
        return match ($this) {
            self::IYZICO => 'iyzico',
            self::STRIPE => 'Stripe',
        };
    }
}
