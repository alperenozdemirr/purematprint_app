<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Http\Services\CheckoutOrderFileService;
use App\Http\Services\FileService;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadOrderFilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 900;

    /**
     * @param  list<array{path: string, original_name: string, extension?: string, size?: int}>  $files
     */
    public function __construct(
        public int $orderId,
        public array $files,
    ) {
    }

    public function handle(FileService $fileService, CheckoutOrderFileService $checkoutOrderFileService): void
    {
        if ($this->files === []) {
            return;
        }

        $order = Order::query()->find($this->orderId);

        if ($order === null) {
            $this->cleanupRemainingTempFiles();

            return;
        }

        $number = 0;

        foreach ($this->files as $fileMeta) {
            $path = (string) ($fileMeta['path'] ?? '');
            $originalName = (string) ($fileMeta['original_name'] ?? basename($path));

            if ($path === '' || ! Storage::disk('local')->exists($path)) {
                continue;
            }

            $number++;

            $fileService->uploadOrderFileFromLocalPath(
                $path,
                $order->id,
                $number,
                $originalName,
                $order->user_id,
            );

            Storage::disk('local')->delete($path);
        }

        $draftId = $this->extractDraftId();

        if ($draftId !== null) {
            Storage::disk('local')->deleteDirectory(CheckoutOrderFileService::TEMP_ROOT.'/'.$draftId);
        }
    }

    private function cleanupRemainingTempFiles(): void
    {
        foreach ($this->files as $fileMeta) {
            $path = (string) ($fileMeta['path'] ?? '');

            if ($path !== '' && Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }
        }

        $draftId = $this->extractDraftId();

        if ($draftId !== null) {
            Storage::disk('local')->deleteDirectory(CheckoutOrderFileService::TEMP_ROOT.'/'.$draftId);
        }
    }

    private function extractDraftId(): ?string
    {
        $firstPath = (string) ($this->files[0]['path'] ?? '');

        if (! str_starts_with($firstPath, CheckoutOrderFileService::TEMP_ROOT.'/')) {
            return null;
        }

        $parts = explode('/', $firstPath);

        return $parts[1] ?? null;
    }
}
