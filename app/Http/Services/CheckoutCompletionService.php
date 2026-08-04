<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Enums\Status;
use App\Http\Services\OrderEmailService;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Support\Facades\DB;

class CheckoutCompletionService
{
    public function __construct(protected OrderEmailService $orderEmailService)
    {
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
        float $paidAmount,
    ): Order {
        return DB::transaction(function () use ($draft, $provider, $providerToken, $providerPaymentId, $paidAmount) {
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
                'address_id' => $draft['address_id'],
                'invoice_address_id' => $draft['address_id'],
                'note' => $draft['note'] ?? null,
                'status' => OrderStatus::PREPARING,
                'invoice_status' => false,
            ], $invoice));

            foreach ($draft['items'] as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                Product::query()
                    ->where('id', $item['product_id'])
                    ->decrement('stock_count', $item['quantity']);
            }

            Payment::create([
                'user_id' => $draft['user_id'],
                'order_id' => $order->id,
                'paid_amount' => $paidAmount,
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
        $this->orderEmailService->sendConfirmationIfNeeded($order);
        $this->orderEmailService->sendAdminNewOrderNotificationIfNeeded($order);
    }
}
