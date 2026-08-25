<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\OrderDesignType;
use App\Enums\OrderSourceChannel;
use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Enums\Status;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Support\Facades\DB;

class CheckoutCompletionService
{
    public function __construct(
        protected OrderEmailService $orderEmailService,
        protected AdminNotificationService $adminNotificationService,
    ) {
    }

    public function validateDraftStock(array $draft): ?string
    {
        foreach ($draft['items'] as $item) {
            $product = Product::query()->find($item['product_id']);

            if ($product === null || $product->status !== Status::ACTIVE) {
                return 'Sepetinizdeki bir ürün artık satışta değil.';
            }

            if ($product->stock_count < $item['quantity']) {
                return $product->title.' için yeterli stok kalmadı.';
            }
        }

        return null;
    }

    public function completeFromDraft(
        array $draft,
        PaymentProvider $provider,
        string $providerToken,
        ?string $providerPaymentId,
        float $paidAmountTry,
        ?string $foreignCurrency = null,
        ?float $foreignAmount = null,
        ?float $fxRate = null,
        ?int $installmentCount = null,
    ): Order {
        return DB::transaction(function () use (
            $draft,
            $provider,
            $providerToken,
            $providerPaymentId,
            $paidAmountTry,
            $foreignCurrency,
            $foreignAmount,
            $fxRate,
            $installmentCount,
        ): Order {
            $summary = $draft['summary'];
            $invoice = $draft['invoice'];

            $order = Order::create(array_merge([
                'user_id' => $draft['user_id'],
                'code' => Order::generateCode(),
                'subtotal' => $summary['subtotal'],
                'is_discount_applied' => $summary['discountApplied'],
                'discount_type' => $summary['discountType'],
                'discount_slice' => (int) round($summary['discountValue'] ?? 0),
                'discount_amount' => $summary['discountAmount'],
                'shipping_is_free' => $summary['shippingFree'],
                'shipping_price' => $summary['shippingCost'],
                'total' => $summary['total'],
                'currency' => 'TRY',
                'foreign_currency' => $foreignCurrency ?? ($summary['chargeCurrency'] ?? null),
                'foreign_amount' => $foreignAmount ?? ($summary['chargeTotal'] ?? null),
                'fx_rate' => $fxRate ?? ($summary['fxRate'] ?? null),
                'address_id' => $draft['address_id'],
                'invoice_address_id' => $draft['address_id'],
                'note' => $draft['note'] ?? null,
                'design_type' => $draft['design_type'] ?? OrderDesignType::default()->value,
                'source_channel' => $draft['source_channel'] ?? OrderSourceChannel::default()->value,
                'status' => OrderStatus::PREPARING,
                'invoice_status' => false,
            ], $invoice));

            foreach ($draft['items'] as $item) {
                $detail = OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                foreach ($item['properties'] ?? [] as $property) {
                    $detail->properties()->create([
                        'group_title' => $property['group_title'],
                        'property_title' => $property['property_title'],
                        'price' => $property['price'],
                        'property_item_id' => $property['property_item_id'] ?? null,
                    ]);
                }

                Product::query()
                    ->where('id', $item['product_id'])
                    ->decrement('stock_count', $item['quantity']);
            }

            Payment::create([
                'user_id' => $draft['user_id'],
                'order_id' => $order->id,
                'paid_amount' => $paidAmountTry,
                'installment_count' => $installmentCount,
                'paid_currency' => 'TRY',
                'paid_amount_foreign' => $foreignAmount ?? ($summary['chargeTotal'] ?? null),
                'foreign_currency' => $foreignCurrency ?? ($summary['chargeCurrency'] ?? null),
                'fx_rate' => $fxRate ?? ($summary['fxRate'] ?? null),
                'status' => PaymentStatus::COMPLETED,
                'provider' => $provider,
                'provider_payment_id' => $providerPaymentId,
                'provider_token' => $providerToken,
            ]);

            ShoppingCart::query()->where('user_id', $draft['user_id'])->delete();

            return $order;
        });
    }

    public function sendConfirmationEmail(Order $order): void
    {
        $order->loadMissing('user');

        $this->adminNotificationService->notifyNewOrder($order);
        $this->adminNotificationService->notifyPaymentCompleted($order);

        $this->orderEmailService->sendConfirmationIfNeeded($order);
        $this->orderEmailService->sendAdminNewOrderNotificationIfNeeded($order);
    }
}
