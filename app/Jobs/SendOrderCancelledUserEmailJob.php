<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Http\Services\QueuedMailService;
use App\Mail\OrderCancelledUserMail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderCancelledUserEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public int $orderId,
        public ?string $refundMessage = null,
    ) {
        $this->onQueue(app(QueuedMailService::class)->queueName());
    }

    public function handle(): void
    {
        $order = Order::query()
            ->with(['user', 'details.product', 'payment'])
            ->find($this->orderId);

        if ($order === null || $order->user?->email === null) {
            return;
        }

        Mail::to($order->user->email)->send(new OrderCancelledUserMail($order, $this->refundMessage));
    }
}
