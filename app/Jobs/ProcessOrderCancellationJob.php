<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Http\Services\OrderCancellationService;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOrderCancellationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public int $orderId,
        public string $clientIp,
    ) {
    }

    public function handle(OrderCancellationService $cancellationService): void
    {
        try {
            $cancellationService->processCancellation($this->orderId, $this->clientIp);
        } catch (\Throwable $exception) {
            if ($this->attempts() >= $this->tries) {
                $order = Order::query()->find($this->orderId);

                if ($order !== null) {
                    $cancellationService->releaseCancellationRequest($order);
                }
            }

            throw $exception;
        }
    }
}
