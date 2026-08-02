<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Http\Services\QueuedMailService;
use App\Mail\TestQueueMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTestEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $recipient = 'ozdemiiralperen@gmail.com')
    {
        $this->onQueue(app(QueuedMailService::class)->queueName());
    }

    public function handle(): void
    {
        Mail::to($this->recipient)->send(new TestQueueMail(now()->format('d.m.Y H:i:s')));
    }
}
