<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\OrderStatus;
use App\Jobs\SendOrderConfirmationEmailJob;
use App\Jobs\SendOrderDeliveredEmailJob;
use App\Jobs\SendOrderShippedEmailJob;
use App\Models\Order;

class OrderEmailService
{
    /**
     * @return array{confirmation: bool, shipped: bool, delivered: bool}
     */
    public function processOrder(Order $order): array
    {
        $order->loadMissing([
            'user',
            'address',
            'payment',
        ]);

        return [
            'confirmation' => $this->sendConfirmationIfNeeded($order),
            'shipped' => $this->sendShippedIfNeeded($order),
            'delivered' => $this->sendDeliveredIfNeeded($order),
        ];
    }

    public function sendConfirmationIfNeeded(Order $order): bool
    {
        if ($order->status === OrderStatus::CANCELLED) {
            return false;
        }

        if ($order->confirmation_email_sent_at !== null || ! filled($order->user?->email)) {
            return false;
        }

        SendOrderConfirmationEmailJob::dispatch($order->id);

        return true;
    }

    public function sendShippedIfNeeded(Order $order): bool
    {
        if ($order->status === OrderStatus::CANCELLED) {
            return false;
        }

        if (! $this->shouldSendShippedEmail($order) || ! filled($order->user?->email)) {
            return false;
        }

        $shipmentKey = $this->shippedEmailShipmentKey($order);

        SendOrderShippedEmailJob::dispatch($order->id, $shipmentKey);

        return true;
    }

    public function sendDeliveredIfNeeded(Order $order): bool
    {
        if ($order->status === OrderStatus::CANCELLED) {
            return false;
        }

        if ($order->delivered_email_sent_at !== null || ! filled($order->user?->email)) {
            return false;
        }

        if ($order->status !== OrderStatus::COMPLETED && $order->delivered_at === null) {
            return false;
        }

        SendOrderDeliveredEmailJob::dispatch($order->id);

        return true;
    }

    public function shouldSendShippedEmail(Order $order): bool
    {
        if ($order->isDomesticShipment()) {
            if ($order->carrier_picked_up_at === null) {
                return false;
            }

            $shipmentKey = $this->shippedEmailShipmentKey($order);

            return $order->shipped_email_shipment_id !== $shipmentKey;
        }

        if (! in_array($order->status, [OrderStatus::SHIPPED, OrderStatus::COMPLETED], true)) {
            return false;
        }

        return $order->shipped_email_sent_at === null;
    }

    public function shippedEmailShipmentKey(Order $order): string
    {
        if ($order->isDomesticShipment()) {
            return (string) ($order->shipink_shipment_id ?? 'none');
        }

        return 'manual';
    }
}
