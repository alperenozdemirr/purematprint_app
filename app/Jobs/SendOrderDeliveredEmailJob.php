<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Mail\OrderDeliveredMail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderDeliveredEmailJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $uniqueFor = 3600;

    public function __construct(public int $orderId)
    {
    }

    public function uniqueId(): string
    {
        return 'order-delivered-'.$this->orderId;
    }

    public function handle(): void
    {
        $order = Order::query()
            ->with(['user', 'details.product', 'address.city', 'address.county'])
            ->find($this->orderId);

        if ($order === null || $order->status === OrderStatus::CANCELLED) {
            return;
        }

        if ($order->delivered_email_sent_at !== null || ! filled($order->user?->email)) {
            return;
        }

        if ($order->status !== OrderStatus::COMPLETED && $order->delivered_at === null) {
            return;
        }

        Mail::to($order->user->email)->send(new OrderDeliveredMail($order));

        $order->update(['delivered_email_sent_at' => now()]);
    }
}
