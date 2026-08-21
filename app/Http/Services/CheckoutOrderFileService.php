<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Support\RandomFileName;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CheckoutOrderFileService
{
    public const TEMP_ROOT = 'checkout_temp';

    public const ALLOWED_EXTENSIONS = ['png', 'pdf', 'psd'];

    /**
     * @param  array<int, UploadedFile>|null  $files
     * @return list<array{path: string, original_name: string, extension: string, size: int}>
     */
    public function storeTemporary(string $draftId, ?array $files): array
    {
        if ($files === null || $files === []) {
            return [];
        }

        $stored = [];
        $directory = self::TEMP_ROOT.'/'.$draftId;

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile || ! $file->isValid()) {
                continue;
            }

            $extension = strtolower((string) $file->getClientOriginalExtension());

            if (! in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
                continue;
            }

            $safeName = RandomFileName::generate($extension);
            $path = $file->storeAs($directory, $safeName, 'local');

            if (! is_string($path) || $path === '') {
                continue;
            }

            $stored[] = [
                'path' => $path,
                'original_name' => $safeName,
                'extension' => $extension,
                'size' => (int) $file->getSize(),
            ];
        }

        return $stored;
    }

    /**
     * @param  array<string, mixed>  $draft
     */
    public function cleanupDraftFiles(array $draft): void
    {
        $files = is_array($draft['files'] ?? null) ? $draft['files'] : [];

        foreach ($files as $file) {
            $path = is_array($file) ? (string) ($file['path'] ?? '') : '';

            if ($path !== '' && Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }
        }

        $draftId = (string) ($draft['draft_id'] ?? '');

        if ($draftId !== '') {
            Storage::disk('local')->deleteDirectory(self::TEMP_ROOT.'/'.$draftId);
        }
    }
}
