<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\OrderActorType;
use App\Enums\OrderDesignRequestType;
use App\Enums\OrderDesignStatus;
use App\Enums\OrderStatus;
use App\Jobs\DeleteOrderCustomerFileJob;
use App\Jobs\SendOrderUpdateNotificationEmailJob;
use App\Jobs\UploadOrderCustomerFileJob;
use App\Jobs\UploadOrderDesignJob;
use App\Models\File;
use App\Models\Order;
use App\Models\OrderDesignRequest;
use App\Support\RandomFileName;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderPreparingFileService
{
    public const TEMP_ROOT = 'order_manage_temp';

    public const ALLOWED_EXTENSIONS = ['png', 'pdf', 'psd', 'jpg', 'jpeg'];

    public function __construct(protected AdminNotificationService $adminNotificationService)
    {
    }

    public function assertPreparing(Order $order): void
    {
        if ($order->status !== OrderStatus::PREPARING) {
            throw ValidationException::withMessages([
                'order' => 'Bu işlem yalnızca sipariş "Hazırlanıyor" durumundayken yapılabilir.',
            ]);
        }
    }

    /**
     * @return array{path: string, original_name: string, extension: string, size: int}
     */
    public function storeTemporary(int $orderId, UploadedFile $file, string $kind = 'file'): array
    {
        $extension = strtolower((string) $file->getClientOriginalExtension());

        if (! in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            throw ValidationException::withMessages([
                'file' => 'İzin verilen dosya türleri: '.implode(', ', self::ALLOWED_EXTENSIONS),
            ]);
        }

        $directory = self::TEMP_ROOT.'/'.$kind.'/'.$orderId.'/'.Str::uuid()->toString();
        $safeName = RandomFileName::generate($extension);
        $path = $file->storeAs($directory, $safeName, 'local');

        if (! is_string($path) || $path === '') {
            throw ValidationException::withMessages([
                'file' => 'Dosya geçici olarak kaydedilemedi.',
            ]);
        }

        return [
            'path' => $path,
            'original_name' => $safeName,
            'extension' => $extension,
            'size' => (int) $file->getSize(),
        ];
    }

    public function queueCustomerFileUpload(Order $order, UploadedFile $file): void
    {
        $this->assertPreparing($order);

        $meta = $this->storeTemporary($order->id, $file, 'customer');

        UploadOrderCustomerFileJob::dispatch($order->id, $meta, (int) auth()->id());
    }

    public function queueCustomerFileDelete(Order $order, File $file): void
    {
        $this->assertPreparing($order);

        $contentType = $file->content_type instanceof \App\Enums\ContentType
            ? $file->content_type->value
            : (string) $file->content_type;

        if ((int) $file->key_id !== (int) $order->id || $contentType !== \App\Enums\ContentType::ORDER_FILE->value) {
            throw ValidationException::withMessages([
                'file' => 'Dosya bu siparişe ait değil.',
            ]);
        }

        DeleteOrderCustomerFileJob::dispatch($order->id, $file->id, (int) auth()->id());
    }

    public function queueDesignUpload(Order $order, UploadedFile $file, ?string $note, int $adminId): void
    {
        $this->assertPreparing($order);

        $meta = $this->storeTemporary($order->id, $file, 'design');

        UploadOrderDesignJob::dispatch($order->id, $meta, $adminId, $note);
    }

    public function approveDesign(Order $order, ?string $note, int $customerId): void
    {
        $this->assertPreparing($order);

        if ($order->design_status === OrderDesignStatus::NONE || $order->designFile === null) {
            throw ValidationException::withMessages([
                'design' => 'Onaylanacak bir tasarım bulunamadı.',
            ]);
        }

        if ($order->design_status === OrderDesignStatus::APPROVED) {
            throw ValidationException::withMessages([
                'design' => 'Tasarım zaten onaylanmış.',
            ]);
        }

        OrderDesignRequest::query()->create([
            'order_id' => $order->id,
            'file_id' => $order->designFile->id,
            'type' => OrderDesignRequestType::APPROVED,
            'actor_type' => OrderActorType::CUSTOMER,
            'actor_id' => $customerId,
            'note' => $note,
        ]);

        $order->update(['design_status' => OrderDesignStatus::APPROVED]);

        SendOrderUpdateNotificationEmailJob::dispatch(
            $order->id,
            'design_approved',
            'Tasarımınız onaylandı',
            $note,
        );

        $this->adminNotificationService->notifyDesignApproved($order, $note);
    }

    public function requestDesignRevision(Order $order, string $note, int $customerId): void
    {
        $this->assertPreparing($order);

        if ($order->design_status === OrderDesignStatus::NONE || $order->designFile === null) {
            throw ValidationException::withMessages([
                'design' => 'Revize talep edilecek bir tasarım bulunamadı.',
            ]);
        }

        if ($order->design_status === OrderDesignStatus::APPROVED) {
            throw ValidationException::withMessages([
                'design' => 'Onaylanmış tasarım için revize talep edilemez.',
            ]);
        }

        OrderDesignRequest::query()->create([
            'order_id' => $order->id,
            'file_id' => $order->designFile->id,
            'type' => OrderDesignRequestType::REVISION_REQUESTED,
            'actor_type' => OrderActorType::CUSTOMER,
            'actor_id' => $customerId,
            'note' => $note,
        ]);

        $order->update(['design_status' => OrderDesignStatus::REVISION_REQUESTED]);

        SendOrderUpdateNotificationEmailJob::dispatch(
            $order->id,
            'design_revision_requested',
            'Tasarım için revize talep ettiniz',
            $note,
        );

        $this->adminNotificationService->notifyDesignRevisionRequested($order, $note);
    }

    public function cleanupTempPath(string $path): void
    {
        if ($path !== '' && Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }

        $directory = dirname($path);

        if ($directory !== '.' && $directory !== '' && Storage::disk('local')->exists($directory)) {
            Storage::disk('local')->deleteDirectory($directory);
        }
    }
}
