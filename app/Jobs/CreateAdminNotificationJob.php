<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\AdminNotificationType;
use App\Models\AdminNotification;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateAdminNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        public string $type,
        public string $title,
        public ?string $body = null,
        public ?int $orderId = null,
        public array $data = [],
    ) {
    }

    public function handle(): void
    {
        $type = AdminNotificationType::tryFrom($this->type);

        if ($type === null || trim($this->title) === '') {
            return;
        }

        $orderId = $this->orderId;

        if ($orderId !== null && ! Order::query()->whereKey($orderId)->exists()) {
            $orderId = null;
        }

        AdminNotification::query()->create([
            'type' => $type,
            'title' => $this->title,
            'body' => $this->body,
            'order_id' => $orderId,
            'data' => $this->data !== [] ? $this->data : null,
        ]);
    }
}
