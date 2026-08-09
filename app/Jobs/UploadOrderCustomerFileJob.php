<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\ContentType;
use App\Enums\OrderStatus;
use App\Http\Services\AdminNotificationService;
use App\Http\Services\FileService;
use App\Http\Services\OrderPreparingFileService;
use App\Models\File;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadOrderCustomerFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 900;

    /**
     * @param  array{path: string, original_name: string, extension?: string, size?: int}  $fileMeta
     */
    public function __construct(
        public int $orderId,
        public array $fileMeta,
        public int $actorId,
    ) {
    }

    public function handle(
        FileService $fileService,
        OrderPreparingFileService $preparingFileService,
        AdminNotificationService $adminNotificationService,
    ): void {

        $path = (string) ($this->fileMeta['path'] ?? '');
        $originalName = (string) ($this->fileMeta['original_name'] ?? basename($path));

        $order = Order::query()->find($this->orderId);

        if ($order === null || $order->status !== OrderStatus::PREPARING || $path === '' || ! Storage::disk('local')->exists($path)) {
            $preparingFileService->cleanupTempPath($path);

            return;
        }

        $existing = File::query()
            ->where('key_id', $order->id)
            ->where('content_type', ContentType::ORDER_FILE->value)
            ->get();

        foreach ($existing as $old) {
            $fileService->imageDelete($old->id, ContentType::ORDER_FILE);
        }

        $fileService->uploadOrderFileFromLocalPath(
            $path,
            $order->id,
            1,
            $originalName,
            $order->user_id,
        );

        $preparingFileService->cleanupTempPath($path);

        SendOrderUpdateNotificationEmailJob::dispatch(
            $order->id,
            'customer_file_uploaded',
            'Sipariş dosyanız güncellendi',
            'Yeni dosya: '.$originalName,
        );

        $adminNotificationService->notifyCustomerFileUploaded($order, $originalName);
    }

    public function failed(?\Throwable $exception): void
    {
        app(OrderPreparingFileService::class)->cleanupTempPath((string) ($this->fileMeta['path'] ?? ''));
    }
}
