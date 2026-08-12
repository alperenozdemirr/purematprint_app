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

    public function refundSettlementNotice(): string
    {
        return match ($this) {
            self::IYZICO => 'İadenin kart veya banka hesabınıza yansıması genellikle 1–7 iş günü sürebilir. Para anında hesabınıza düşmeyebilir.',
            self::STRIPE => 'İadenin kart veya banka hesabınıza yansıması genellikle 5–10 iş günü sürebilir. Para anında hesabınıza düşmeyebilir.',
        };
    }
}
