<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Services\MediaStreamService;
use App\Support\MediaPath;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    public function __construct(protected MediaStreamService $mediaStream)
    {
    }

    public function show(Request $request, string $path): StreamedResponse|Response
    {
        if (! MediaPath::isAllowed($path)) {
            abort(404);
        }

        $disk = $this->mediaStream->disk();

        if (! $disk->exists($path)) {
            abort(404);
        }

        $mime = $disk->mimeType($path) ?? 'application/octet-stream';
        $size = (int) ($disk->size($path) ?? 0);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = $this->mediaStream->resolveMimeType($mime, $extension);

        $headers = [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="media.'.($extension ?: 'bin').'"',
            'Cache-Control' => 'public, max-age=31536000',
            'Accept-Ranges' => 'bytes',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Length' => (string) $size,
        ];

        if ($request->isMethod('HEAD')) {
            return response('', 200, $headers);
        }

        $rangeHeader = $request->header('Range');

        if ($rangeHeader && preg_match('/bytes=(\d*)-(\d*)/', $rangeHeader, $matches)) {
            return $this->streamRangeResponse($path, $size, $matches, $mime, $extension, $headers);
        }

        return response()->stream(function () use ($disk, $path) {
            $stream = $disk->readStream($path);

            if (is_resource($stream)) {
                fpassthru($stream);
                fclose($stream);
            }
        }, 200, $headers);
    }

    /**
     * @param  array<int, string>  $matches
     */
    private function streamRangeResponse(
        string $path,
        int $size,
        array $matches,
        string $mime,
        string $extension,
        array $baseHeaders,
    ): StreamedResponse|Response {
        $start = $matches[1] !== '' ? (int) $matches[1] : 0;
        $end = $matches[2] !== '' ? (int) $matches[2] : ($size - 1);

        if ($start > $end || $start >= $size) {
            return response('', 416, [
                'Content-Range' => 'bytes */'.$size,
            ]);
        }

        $end = min($end, $size - 1);
        $length = ($end - $start) + 1;

        $result = $this->mediaStream->s3Client()->getObject([
            'Bucket' => $this->mediaStream->bucket(),
            'Key' => $path,
            'Range' => "bytes={$start}-{$end}",
        ]);

        $body = $result['Body'];

        return response()->stream(function () use ($body) {
            while (! $body->eof()) {
                echo $body->read(8192);
            }
        }, 206, array_merge($baseHeaders, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="media.'.($extension ?: 'bin').'"',
            'Content-Length' => (string) $length,
            'Content-Range' => "bytes {$start}-{$end}/{$size}",
        ]));
    }
}
