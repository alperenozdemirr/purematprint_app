<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $changedAt,
        public string $panel = 'user',
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'PureMatPrint — Şifreniz Değiştirildi',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.password-changed',
        );
    }
}
