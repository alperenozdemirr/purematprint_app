<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Http\Services\QueuedMailService;
use App\Mail\OrderCancelledAdminNotificationMail;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderCancelledAdminNotificationJob implements ShouldQueue
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
            ->with(['user', 'details.product', 'address.city', 'address.county', 'payment'])
            ->find($this->orderId);

        if ($order === null) {
            return;
        }

        $recipients = Setting::current()->orderNotificationEmails();

        if ($recipients === []) {
            return;
        }

        foreach ($recipients as $recipient) {
            Mail::to($recipient)->send(new OrderCancelledAdminNotificationMail($order, $this->refundMessage));
        }
    }
}
