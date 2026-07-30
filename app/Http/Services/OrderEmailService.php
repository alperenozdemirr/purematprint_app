<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\OrderStatus;
use App\Mail\OrderConfirmationMail;
use App\Mail\OrderDeliveredMail;
use App\Mail\OrderShippedMail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderEmailService
{
    /**
     * @return array{confirmation: bool, shipped: bool, delivered: bool}
     */
    public function processOrder(Order $order): array
    {
        $order->loadMissing([
            'user',
            'details.product',
            'address.city',
            'address.county',
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

        if ($order->confirmation_email_sent_at !== null) {
            return false;
        }

        if (! filled($order->user?->email)) {
            return false;
        }

        if (! $order->relationLoaded('details')) {
            $order->load(['details.product', 'address.city', 'address.county']);
        }

        $claimed = Order::query()
            ->where('id', $order->id)
            ->whereNull('confirmation_email_sent_at')
            ->update(['confirmation_email_sent_at' => now()]);

        if ($claimed === 0) {
            return false;
        }

        try {
            Mail::to($order->user->email)->send(new OrderConfirmationMail($order));

            return true;
        } catch (\Throwable $exception) {
            Order::query()
                ->where('id', $order->id)
                ->update(['confirmation_email_sent_at' => null]);

            report($exception);

            return false;
        }
    }

    public function sendShippedIfNeeded(Order $order): bool
    {
        if ($order->status === OrderStatus::CANCELLED) {
            return false;
        }

        if (! $this->shouldSendShippedEmail($order)) {
            return false;
        }

        if (! filled($order->user?->email)) {
            return false;
        }

        if (! $order->relationLoaded('details')) {
            $order->load(['details.product', 'address.city', 'address.county']);
        }

        $shipmentKey = $this->shippedEmailShipmentKey($order);

        $claimed = Order::query()
            ->where('id', $order->id)
            ->where(function ($query) use ($shipmentKey) {
                $query->whereNull('shipped_email_shipment_id')
                    ->orWhere('shipped_email_shipment_id', '!=', $shipmentKey);
            })
            ->update([
                'shipped_email_sent_at' => now(),
                'shipped_email_shipment_id' => $shipmentKey,
            ]);

        if ($claimed === 0) {
            return false;
        }

        try {
            Mail::to($order->user->email)->send(new OrderShippedMail($order));

            return true;
        } catch (\Throwable $exception) {
            Order::query()
                ->where('id', $order->id)
                ->where('shipped_email_shipment_id', $shipmentKey)
                ->update([
                    'shipped_email_sent_at' => null,
                    'shipped_email_shipment_id' => null,
                ]);

            report($exception);

            return false;
        }
    }

    public function sendDeliveredIfNeeded(Order $order): bool
    {
        if ($order->status === OrderStatus::CANCELLED) {
            return false;
        }

        if ($order->delivered_email_sent_at !== null) {
            return false;
        }

        if ($order->status !== OrderStatus::COMPLETED && $order->delivered_at === null) {
            return false;
        }

        if (! filled($order->user?->email)) {
            return false;
        }

        if (! $order->relationLoaded('details')) {
            $order->load(['details.product', 'address.city', 'address.county']);
        }

        $claimed = Order::query()
            ->where('id', $order->id)
            ->whereNull('delivered_email_sent_at')
            ->update(['delivered_email_sent_at' => now()]);

        if ($claimed === 0) {
            return false;
        }

        try {
            Mail::to($order->user->email)->send(new OrderDeliveredMail($order));

            return true;
        } catch (\Throwable $exception) {
            Order::query()
                ->where('id', $order->id)
                ->update(['delivered_email_sent_at' => null]);

            report($exception);

            return false;
        }
    }

    private function shouldSendShippedEmail(Order $order): bool
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

    private function shippedEmailShipmentKey(Order $order): string
    {
        if ($order->isDomesticShipment()) {
            return (string) ($order->shipink_shipment_id ?? 'none');
        }

        return 'manual';
    }
}
