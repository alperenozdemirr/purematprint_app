<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\AdminNotificationType;
use App\Jobs\CreateAdminNotificationJob;
use App\Models\AdminNotification;
use App\Models\Order;

class AdminNotificationService
{
    public function notify(
        AdminNotificationType $type,
        string $title,
        ?string $body = null,
        ?Order $order = null,
        array $data = [],
    ): void {
        CreateAdminNotificationJob::dispatch(
            $type->value,
            $title,
            $body,
            $order?->id,
            array_merge($data, array_filter([
                'order_code' => $order?->code,
            ])),
        );
    }

    public function notifyNewOrder(Order $order): void
    {
        $customer = $order->user?->name ?? 'Müşteri';

        $this->notify(
            AdminNotificationType::NEW_ORDER,
            'Yeni sipariş: '.$order->code,
            $customer.' yeni bir sipariş oluşturdu.',
            $order,
        );
    }

    public function notifyPaymentCompleted(Order $order): void
    {
        $amount = number_format((float) $order->total, 0, ',', '.').' ₺';

        $this->notify(
            AdminNotificationType::PAYMENT_COMPLETED,
            'Ödeme tamamlandı: '.$order->code,
            $amount.' tutarında ödeme alındı.',
            $order,
        );
    }

    public function notifyCustomerFileUploaded(Order $order, ?string $fileName = null): void
    {
        $this->notify(
            AdminNotificationType::CUSTOMER_FILE_UPLOADED,
            'Dosya yüklendi: '.$order->code,
            $fileName
                ? 'Müşteri dosya yükledi: '.$fileName
                : 'Müşteri sipariş dosyasını güncelledi.',
            $order,
        );
    }

    public function notifyDesignApproved(Order $order, ?string $note = null): void
    {
        $this->notify(
            AdminNotificationType::DESIGN_APPROVED,
            'Tasarım onaylandı: '.$order->code,
            $note ?: 'Müşteri tasarımı onayladı.',
            $order,
        );
    }

    public function notifyDesignRevisionRequested(Order $order, ?string $note = null): void
    {
        $this->notify(
            AdminNotificationType::DESIGN_REVISION_REQUESTED,
            'Revize talebi: '.$order->code,
            $note ?: 'Müşteri tasarım için revize istedi.',
            $order,
        );
    }

    public function notifyOrderCancelled(Order $order, ?string $refundMessage = null): void
    {
        $this->notify(
            AdminNotificationType::ORDER_CANCELLED,
            'Sipariş iptal edildi: '.$order->code,
            $refundMessage ?: 'Müşteri siparişi iptal etti ve ödeme iadesi tamamlandı.',
            $order,
        );
    }

    public function notifyOrderCancellationFailed(Order $order, string $reason): void
    {
        $this->notify(
            AdminNotificationType::ORDER_CANCELLATION_FAILED,
            'İptal / iade hatası: '.$order->code,
            $reason,
            $order,
        );
    }

    public function unreadCount(): int
    {
        return AdminNotification::query()->unread()->count();
    }

    public function recent(int $limit = 8)
    {
        return AdminNotification::query()
            ->with('order:id,code')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
