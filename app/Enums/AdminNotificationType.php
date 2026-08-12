<?php

declare(strict_types=1);

namespace App\Enums;

enum AdminNotificationType: string
{
    case NEW_ORDER = 'new_order';
    case PAYMENT_COMPLETED = 'payment_completed';
    case CUSTOMER_FILE_UPLOADED = 'customer_file_uploaded';
    case DESIGN_APPROVED = 'design_approved';
    case DESIGN_REVISION_REQUESTED = 'design_revision_requested';
    case ORDER_CANCELLED = 'order_cancelled';
    case ORDER_CANCELLATION_FAILED = 'order_cancellation_failed';

    public function label(): string
    {
        return match ($this) {
            self::NEW_ORDER => 'Yeni Sipariş',
            self::PAYMENT_COMPLETED => 'Ödeme Tamamlandı',
            self::CUSTOMER_FILE_UPLOADED => 'Dosya Yüklendi',
            self::DESIGN_APPROVED => 'Tasarım Onaylandı',
            self::DESIGN_REVISION_REQUESTED => 'Revize Talebi',
            self::ORDER_CANCELLED => 'Sipariş İptali',
            self::ORDER_CANCELLATION_FAILED => 'İptal / İade Hatası',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
