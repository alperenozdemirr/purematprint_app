<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\ContentType;
use App\Enums\FileType;
use App\Models\File;
use App\Support\MediaPath;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileService
{
    protected const addToDatabase = true;

    public function imageUpload($file, $contentType = null, $keyId = null, $number = null)
    {
        $file_type = FileType::IMAGE;
        $baseDirectory = null;
        $user = null;

        if ($contentType == null || $contentType == ContentType::USER) {
            $baseDirectory = MediaPath::USER;
            $contentType = ContentType::USER;
            $user = Auth::user()->id;
        } elseif ($contentType == ContentType::OTHER) {
            $baseDirectory = MediaPath::OTHER;
            $contentType = ContentType::OTHER;
        } elseif ($contentType == ContentType::PRODUCT) {
            $baseDirectory = MediaPath::PRODUCT;
            $contentType = ContentType::PRODUCT;
        } elseif ($contentType == ContentType::BANNER) {
            $baseDirectory = MediaPath::BANNER;
            $contentType = ContentType::BANNER;
        } elseif ($contentType == ContentType::COLLECTION) {
            $baseDirectory = MediaPath::COLLECTION;
            $contentType = ContentType::COLLECTION;
        } elseif ($contentType == ContentType::BLOG) {
            $baseDirectory = MediaPath::BLOG;
            $contentType = ContentType::BLOG;
        }

        if ($baseDirectory === null) {
            throw new \InvalidArgumentException('Geçersiz içerik tipi veya dosya yolu.');
        }

        $fileName = $file->hashName();
        $relativePath = $baseDirectory.'/'.$fileName;

        $content = file_get_contents($file->getRealPath());

        if ($content === false) {
            throw new \RuntimeException('Dosya okunamadı.');
        }

        Storage::disk('r2')->put($relativePath, $content);

        if (self::addToDatabase) {
            return $this->storeFile($fileName, $file_type, $contentType, $user, $keyId, $number);
        }

        return false;
    }

    public function putBinary(string $baseDirectory, string $fileName, string $content): string
    {
        $relativePath = $baseDirectory.'/'.$fileName;
        Storage::disk('r2')->put($relativePath, $content);

        return $relativePath;
    }

    protected function storeFile($fileName, $file_type, $content_type, $user = null, $keyId = null, $number = null)
    {
        return File::create([
            'file_name' => $fileName,
            'user_id' => $user,
            'key_id' => $keyId,
            'file_type' => $file_type,
            'content_type' => $content_type,
            'number' => $number,
        ]);
    }

    public function imageDelete($imageId, $contentType = null)
    {
        $baseDirectory = match (true) {
            $contentType == null, $contentType == ContentType::USER => MediaPath::USER,
            $contentType == ContentType::OTHER => MediaPath::OTHER,
            $contentType == ContentType::PRODUCT => MediaPath::PRODUCT,
            $contentType == ContentType::BANNER => MediaPath::BANNER,
            $contentType == ContentType::COLLECTION => MediaPath::COLLECTION,
            $contentType == ContentType::BLOG => MediaPath::BLOG,
            default => null,
        };

        $deleteItem = File::find($imageId);

        if ($deleteItem && $baseDirectory !== null) {
            $relativePath = $baseDirectory.'/'.$deleteItem->file_name;
            $deleteItem->delete();
            Storage::disk('r2')->delete($relativePath);

            return true;
        }

        return false;
    }
}
