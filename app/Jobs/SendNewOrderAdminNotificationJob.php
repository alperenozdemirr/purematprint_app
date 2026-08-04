<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Http\Services\QueuedMailService;
use App\Mail\NewOrderAdminNotificationMail;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewOrderAdminNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $orderId)
    {
        $this->onQueue(app(QueuedMailService::class)->queueName());
    }

    public function handle(): void
    {
        if (! $this->claimNotification()) {
            return;
        }

        $order = Order::query()
            ->with(['user', 'details.product', 'address.city', 'address.county', 'payment'])
            ->find($this->orderId);

        if ($order === null || $order->status === OrderStatus::CANCELLED) {
            $this->releaseNotificationClaim();

            return;
        }

        $recipients = Setting::current()->orderNotificationEmails();

        if ($recipients === []) {
            $this->releaseNotificationClaim();

            return;
        }

        try {
            foreach ($recipients as $recipient) {
                Mail::to($recipient)->send(new NewOrderAdminNotificationMail($order));
            }
        } catch (\Throwable $exception) {
            $this->releaseNotificationClaim();

            throw $exception;
        }
    }

    private function claimNotification(): bool
    {
        return Order::query()
            ->whereKey($this->orderId)
            ->whereNull('admin_notification_sent_at')
            ->where('status', '!=', OrderStatus::CANCELLED->value)
            ->update(['admin_notification_sent_at' => now()]) === 1;
    }

    private function releaseNotificationClaim(): void
    {
        Order::query()
            ->whereKey($this->orderId)
            ->whereNotNull('admin_notification_sent_at')
            ->update(['admin_notification_sent_at' => null]);
    }
}
