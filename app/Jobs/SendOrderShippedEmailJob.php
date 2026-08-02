<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Http\Services\OrderEmailService;
use App\Mail\OrderShippedMail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderShippedEmailJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $uniqueFor = 3600;

    public function __construct(public int $orderId, public string $shipmentKey)
    {
    }

    public function uniqueId(): string
    {
        return 'order-shipped-'.$this->orderId.'-'.$this->shipmentKey;
    }

    public function handle(OrderEmailService $orderEmailService): void
    {
        $order = Order::query()
            ->with(['user', 'details.product', 'address.city', 'address.county', 'address'])
            ->find($this->orderId);

        if ($order === null || $order->status === OrderStatus::CANCELLED) {
            return;
        }

        if (! $orderEmailService->shouldSendShippedEmail($order) || ! filled($order->user?->email)) {
            return;
        }

        if ($order->shipped_email_shipment_id === $this->shipmentKey) {
            return;
        }

        Mail::to($order->user->email)->send(new OrderShippedMail($order));

        $order->update([
            'shipped_email_sent_at' => now(),
            'shipped_email_shipment_id' => $this->shipmentKey,
        ]);
    }
}
