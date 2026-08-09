<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Http\Services\QueuedMailService;
use App\Mail\OrderUpdateNotificationMail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderUpdateNotificationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $orderId,
        public string $eventKey,
        public string $headline,
        public ?string $note = null,
    ) {
        $this->onQueue(app(QueuedMailService::class)->queueName());
    }

    public function handle(): void
    {
        $order = Order::query()
            ->with(['user', 'designFile', 'designRequests' => fn ($q) => $q->latest()->limit(5)])
            ->find($this->orderId);

        if ($order === null || $order->status === OrderStatus::CANCELLED || ! filled($order->user?->email)) {
            return;
        }

        Mail::to($order->user->email)->send(new OrderUpdateNotificationMail(
            $order,
            $this->eventKey,
            $this->headline,
            $this->note,
        ));
    }
}
