<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\SendTestEmailJob;
use Illuminate\Console\Command;

class SendTestQueueJobCommand extends Command
{
    protected $signature = 'queue:test-job
                            {--email=ozdemiiralperen@gmail.com : Test mailinin gönderileceği adres}';

    protected $description = 'Kuyruk testi için ozdemiiralperen@gmail.com adresine test maili job\'u ekler';

    public function handle(): int
    {
        $email = (string) $this->option('email');

        SendTestEmailJob::dispatch($email);

        $this->info("Test job kuyruğa eklendi: {$email}");
        $this->line('Job\'u işlemek için: php artisan queue:work');

        return self::SUCCESS;
    }
}
