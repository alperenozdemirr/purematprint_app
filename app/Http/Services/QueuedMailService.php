<?php

declare(strict_types=1);

namespace App\Http\Services;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class QueuedMailService
{
    public function queue(string $email, Mailable $mailable): void
    {
        Mail::to($email)->queue(
            $mailable->onQueue($this->queueName())
        );
    }

    public function queueName(): string
    {
        return (string) config('queue.mail_queue', 'default');
    }
}
