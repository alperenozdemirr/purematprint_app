<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\ContentType;
use App\Enums\OrderStatus;
use App\Http\Services\FileService;
use App\Models\File;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteOrderCustomerFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public int $orderId,
        public int $fileId,
        public int $actorId,
    ) {
    }

    public function handle(FileService $fileService): void
    {
        $order = Order::query()->find($this->orderId);

        if ($order === null || $order->status !== OrderStatus::PREPARING) {
            return;
        }

        $file = File::query()
            ->where('id', $this->fileId)
            ->where('key_id', $order->id)
            ->where('content_type', ContentType::ORDER_FILE->value)
            ->first();

        if ($file === null) {
            return;
        }

        $name = $file->displayName();
        $fileService->imageDelete($file->id, ContentType::ORDER_FILE);

        SendOrderUpdateNotificationEmailJob::dispatch(
            $order->id,
            'customer_file_deleted',
            'Sipariş dosyanız silindi',
            'Silinen dosya: '.$name,
        );
    }
}
