<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Jobs\ProcessOrderCancellationJob;
use App\Jobs\SendOrderCancelledAdminNotificationJob;
use App\Jobs\SendOrderCancelledUserEmailJob;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderCancellationService
{
    public function __construct(
        protected PaymentRefundService $paymentRefundService,
        protected ShipinkShipmentService $shipinkService,
        protected AdminNotificationService $adminNotificationService,
    ) {
    }

    public function requestSelfCancellation(Order $order, string $clientIp): void
    {
        if (! $order->canSelfCancel()) {
            throw ValidationException::withMessages([
                'cancel' => 'Bu sipariş artık iptal edilemez.',
            ]);
        }

        if ($order->cancellation_requested_at !== null) {
            throw ValidationException::withMessages([
                'cancel' => 'İptal talebiniz zaten alındı ve işleniyor.',
            ]);
        }

        $claimed = Order::query()
            ->whereKey($order->id)
            ->where('status', OrderStatus::PREPARING)
            ->whereNull('cancellation_requested_at')
            ->whereNull('cancelled_at')
            ->update(['cancellation_requested_at' => now()]);

        if ($claimed !== 1) {
            throw ValidationException::withMessages([
                'cancel' => 'İptal talebi oluşturulamadı. Lütfen sayfayı yenileyip tekrar deneyin.',
            ]);
        }

        ProcessOrderCancellationJob::dispatch($order->id, $clientIp);
    }

    public function processCancellation(int $orderId, string $clientIp): void
    {
        $order = Order::query()
            ->with(['payment', 'details', 'user'])
            ->find($orderId);

        if ($order === null) {
            return;
        }

        if ($order->status === OrderStatus::CANCELLED) {
            return;
        }

        if ($order->cancellation_requested_at === null) {
            return;
        }

        if (! $order->canSelfCancel(includeRequested: true)) {
            $this->releaseCancellationRequest($order);
            $this->adminNotificationService->notifyOrderCancellationFailed(
                $order,
                'Sipariş iptal süresi doldu veya durum uygun değil.',
            );

            return;
        }

        $payment = $order->payment;

        if ($payment === null || $payment->status !== PaymentStatus::COMPLETED) {
            $this->releaseCancellationRequest($order);
            $this->adminNotificationService->notifyOrderCancellationFailed(
                $order,
                'Tamamlanmış ödeme kaydı bulunamadı.',
            );

            return;
        }

        if ($order->hasShipinkShipment()) {
            $this->shipinkService->cancelShipmentForCancelledOrder($order);
            $order->refresh();
        }

        $refundResult = $this->paymentRefundService->refund($payment, $clientIp);

        if (! $refundResult->success) {
            $this->adminNotificationService->notifyOrderCancellationFailed(
                $order,
                $refundResult->message ?? 'Ödeme iadesi başarısız.',
            );

            throw new \RuntimeException($refundResult->message ?? 'Ödeme iadesi başarısız.');
        }

        DB::transaction(function () use ($order, $payment, $refundResult) {
            foreach ($order->details as $detail) {
                Product::query()
                    ->whereKey($detail->product_id)
                    ->increment('stock_count', (int) $detail->quantity);
            }

            $payment->update([
                'status' => PaymentStatus::REFUNDED,
                'refunded_at' => now(),
            ]);

            $order->update([
                'status' => OrderStatus::CANCELLED,
                'cancelled_at' => now(),
            ]);
        });

        $order->refresh()->load(['payment', 'details.product', 'address.city', 'address.county', 'user']);

        $this->adminNotificationService->notifyOrderCancelled($order, $refundResult->message);
        SendOrderCancelledUserEmailJob::dispatch($order->id, $refundResult->message);
        SendOrderCancelledAdminNotificationJob::dispatch($order->id, $refundResult->message);
    }

    public function releaseCancellationRequest(Order $order): void
    {
        Order::query()
            ->whereKey($order->id)
            ->whereNotNull('cancellation_requested_at')
            ->whereNull('cancelled_at')
            ->update(['cancellation_requested_at' => null]);
    }
}
