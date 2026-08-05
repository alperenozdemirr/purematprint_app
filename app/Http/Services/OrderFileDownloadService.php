<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\ContentType;
use App\Models\File;
use App\Models\Order;
use App\Support\MediaPath;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderFileDownloadService
{
    public function __construct(protected MediaStreamService $mediaStream)
    {
    }

    public function download(Order $order, int $fileId): StreamedResponse
    {
        $file = File::query()
            ->where('id', $fileId)
            ->where('key_id', $order->id)
            ->whereIn('content_type', [
                ContentType::ORDER_FILE->value,
                ContentType::ORDER_INVOICE->value,
            ])
            ->firstOrFail();

        $contentType = ContentType::from((string) $file->content_type);
        $path = MediaPath::relativePath($contentType, $file->file_name);
        $disk = $this->mediaStream->disk();

        if (! $disk->exists($path)) {
            abort(404);
        }

        $extension = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
        $mime = $this->mediaStream->resolveMimeType(
            $disk->mimeType($path) ?? 'application/octet-stream',
            $extension,
        );

        $downloadName = $file->displayName();

        return response()->streamDownload(function () use ($disk, $path) {
            $stream = $disk->readStream($path);

            if (is_resource($stream)) {
                fpassthru($stream);
                fclose($stream);
            }
        }, $downloadName, [
            'Content-Type' => $mime,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
