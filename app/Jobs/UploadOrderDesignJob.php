<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\OrderActorType;
use App\Enums\OrderDesignRequestType;
use App\Enums\OrderDesignStatus;
use App\Enums\OrderStatus;
use App\Http\Services\FileService;
use App\Http\Services\OrderPreparingFileService;
use App\Models\Order;
use App\Models\OrderDesignRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadOrderDesignJob implements ShouldQueue
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
        public int $adminId,
        public ?string $note = null,
    ) {
    }

    public function handle(FileService $fileService, OrderPreparingFileService $preparingFileService): void
    {
        $path = (string) ($this->fileMeta['path'] ?? '');
        $originalName = (string) ($this->fileMeta['original_name'] ?? basename($path));

        $order = Order::query()->find($this->orderId);

        if ($order === null || $order->status !== OrderStatus::PREPARING || $path === '' || ! Storage::disk('local')->exists($path)) {
            $preparingFileService->cleanupTempPath($path);

            return;
        }

        $file = $fileService->uploadOrderDesignFromLocalPath(
            $path,
            $order->id,
            $originalName,
            $this->adminId,
        );

        $preparingFileService->cleanupTempPath($path);

        OrderDesignRequest::query()->create([
            'order_id' => $order->id,
            'file_id' => $file->id,
            'type' => OrderDesignRequestType::DESIGN_UPLOADED,
            'actor_type' => OrderActorType::ADMIN,
            'actor_id' => $this->adminId,
            'note' => $this->note,
        ]);

        $order->update(['design_status' => OrderDesignStatus::AWAITING_APPROVAL]);

        SendOrderUpdateNotificationEmailJob::dispatch(
            $order->id,
            'design_uploaded',
            'Siparişiniz için tasarım yüklendi',
            $this->note,
        );
    }

    public function failed(?\Throwable $exception): void
    {
        app(OrderPreparingFileService::class)->cleanupTempPath((string) ($this->fileMeta['path'] ?? ''));
    }
}
