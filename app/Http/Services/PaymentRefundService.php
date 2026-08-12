<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\PaymentProvider;
use App\Models\Payment;
use App\Support\PaymentRefundResult;

class PaymentRefundService
{
    public function __construct(
        protected IyzicoPaymentService $iyzicoService,
        protected StripePaymentService $stripeService,
    ) {
    }

    public function refund(Payment $payment, string $clientIp): PaymentRefundResult
    {
        $amount = (float) $payment->paid_amount;
        $providerPaymentId = (string) ($payment->provider_payment_id ?? '');

        return match ($payment->provider) {
            PaymentProvider::IYZICO => $this->iyzicoService->refundPayment($providerPaymentId, $amount, $clientIp),
            PaymentProvider::STRIPE => $this->stripeService->refundPayment(
                $providerPaymentId,
                $payment->provider_token,
                $amount,
            ),
        };
    }
}
