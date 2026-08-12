<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCancelledAdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public ?string $refundMessage = null,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'PureMatPrint — Sipariş İptal Edildi ('.$this->order->code.')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.order-cancelled-admin',
        );
    }
}
