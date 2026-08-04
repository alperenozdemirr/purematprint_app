<?php

declare(strict_types=1);

namespace App\Http\Services;

use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;

class MediaStreamService
{
    public function disk()
    {
        return Storage::disk('r2');
    }

    public function bucket(): string
    {
        return (string) config('filesystems.disks.r2.bucket');
    }

    public function s3Client(): S3Client
    {
        $config = config('filesystems.disks.r2');

        return new S3Client([
            'version' => 'latest',
            'region' => (string) ($config['region'] ?? 'auto'),
            'endpoint' => (string) ($config['endpoint'] ?? ''),
            'use_path_style_endpoint' => (bool) ($config['use_path_style_endpoint'] ?? true),
            'credentials' => [
                'key' => (string) ($config['key'] ?? ''),
                'secret' => (string) ($config['secret'] ?? ''),
            ],
        ]);
    }

    public function resolveMimeType(string $mime, string $extension): string
    {
        if (! in_array($mime, ['application/octet-stream', 'binary/octet-stream'], true)) {
            return $mime;
        }

        return match ($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'avif' => 'image/avif',
            'bmp' => 'image/bmp',
            'svg' => 'image/svg+xml',
            'mp4' => 'video/mp4',
            'mov' => 'video/quicktime',
            'webm' => 'video/webm',
            'pdf' => 'application/pdf',
            'tif', 'tiff' => 'image/tiff',
            'eps' => 'application/postscript',
            'ai' => 'application/postscript',
            'psd' => 'image/vnd.adobe.photoshop',
            default => $mime,
        };
    }
}
