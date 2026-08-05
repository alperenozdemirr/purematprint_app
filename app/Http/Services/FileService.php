<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\ContentType;
use App\Enums\FileType;
use App\Models\File;
use App\Support\MediaPath;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        } elseif ($contentType == ContentType::COMMENT) {
            $baseDirectory = MediaPath::COMMENT;
            $contentType = ContentType::COMMENT;
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

    /**
     * Yerel diskteki bir dosyayı R2'ye (order_file) yükler. Büyük dosyalar için stream kullanır.
     */
    public function uploadOrderFileFromLocalPath(
        string $localRelativePath,
        int $orderId,
        int $number,
        string $originalName,
        ?int $userId = null,
    ): File {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION) ?: pathinfo($localRelativePath, PATHINFO_EXTENSION));
        $fileName = Str::uuid()->toString().($extension !== '' ? '.'.$extension : '');
        $relativePath = MediaPath::ORDER_FILE.'/'.$fileName;

        $absolutePath = Storage::disk('local')->path($localRelativePath);

        if (! is_file($absolutePath)) {
            throw new \RuntimeException('Geçici sipariş dosyası bulunamadı: '.$localRelativePath);
        }

        $stream = fopen($absolutePath, 'rb');

        if ($stream === false) {
            throw new \RuntimeException('Sipariş dosyası okunamadı.');
        }

        try {
            Storage::disk('r2')->put($relativePath, $stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        return $this->storeFile(
            $fileName,
            FileType::FILE,
            ContentType::ORDER_FILE,
            $userId,
            $orderId,
            $number,
            $originalName,
        );
    }

    /**
     * Sipariş fatura PDF'ini R2'ye yükler. Varsa eski faturayı siler (tek fatura).
     */
    public function uploadOrderInvoice(UploadedFile $file, int $orderId, ?int $userId = null): File
    {
        $existing = File::query()
            ->where('key_id', $orderId)
            ->where('content_type', ContentType::ORDER_INVOICE->value)
            ->get();

        foreach ($existing as $old) {
            $this->imageDelete($old->id, ContentType::ORDER_INVOICE);
        }

        $originalName = (string) $file->getClientOriginalName();
        $extension = strtolower((string) $file->getClientOriginalExtension()) ?: 'pdf';
        $fileName = Str::uuid()->toString().'.'.$extension;
        $relativePath = MediaPath::ORDER_INVOICE.'/'.$fileName;

        $stream = fopen($file->getRealPath(), 'rb');

        if ($stream === false) {
            throw new \RuntimeException('Fatura dosyası okunamadı.');
        }

        try {
            Storage::disk('r2')->put($relativePath, $stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        return $this->storeFile(
            $fileName,
            FileType::FILE,
            ContentType::ORDER_INVOICE,
            $userId,
            $orderId,
            1,
            $originalName !== '' ? $originalName : $fileName,
        );
    }

    public function putBinary(string $baseDirectory, string $fileName, string $content): string
    {
        $relativePath = $baseDirectory.'/'.$fileName;
        Storage::disk('r2')->put($relativePath, $content);

        return $relativePath;
    }

    protected function storeFile(
        $fileName,
        $file_type,
        $content_type,
        $user = null,
        $keyId = null,
        $number = null,
        ?string $originalName = null,
    ) {
        return File::create([
            'file_name' => $fileName,
            'original_name' => $originalName,
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
            $contentType == ContentType::COMMENT => MediaPath::COMMENT,
            $contentType == ContentType::ORDER_FILE => MediaPath::ORDER_FILE,
            $contentType == ContentType::ORDER_INVOICE => MediaPath::ORDER_INVOICE,
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
