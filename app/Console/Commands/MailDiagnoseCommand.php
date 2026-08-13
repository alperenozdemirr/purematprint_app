<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MailDiagnoseCommand extends Command
{
    protected $signature = 'mail:diagnose';

    protected $description = 'Aktif mail yapılandırmasını gösterir (canlıda .env değişikliği sonrası kontrol için)';

    public function handle(): int
    {
        $configCached = is_file(base_path('bootstrap/cache/config.php'));

        $this->line('Config cache: '.($configCached ? 'EVET ( .env değişiklikleri tek başına uygulanmaz )' : 'Hayır'));
        $this->newLine();

        $smtp = (array) config('mail.mailers.smtp', []);
        $mailUrl = config('mail.mailers.smtp.url') ?? env('MAIL_URL');

        $this->table(
            ['Ayar', 'Değer'],
            [
                ['MAIL_MAILER (default)', (string) config('mail.default')],
                ['MAIL_FROM_ADDRESS', (string) config('mail.from.address')],
                ['MAIL_FROM_NAME', (string) config('mail.from.name')],
                ['MAIL_APP_URL', (string) config('mail.app_url')],
                ['MAIL_URL (DSN)', $mailUrl ? $this->maskMailUrl((string) $mailUrl) : '(boş — host/port/user ayrı kullanılır)'],
                ['MAIL_HOST', (string) ($smtp['host'] ?? '')],
                ['MAIL_PORT', (string) ($smtp['port'] ?? '')],
                ['MAIL_ENCRYPTION', (string) ($smtp['encryption'] ?? '')],
                ['MAIL_USERNAME', (string) ($smtp['username'] ?? '')],
                ['MAIL_PASSWORD', $this->maskSecret($smtp['password'] ?? null)],
                ['QUEUE_CONNECTION', (string) config('queue.default')],
                ['MAIL_QUEUE', (string) config('queue.mail_queue')],
            ],
        );

        if ($configCached) {
            $this->newLine();
            $this->warn('Config önbelleği açık. .env güncelledikten sonra:');
            $this->line('  php artisan config:clear');
            $this->line('  php artisan config:cache   (canlıda önerilir)');
        }

        if (in_array(config('queue.default'), ['redis', 'database'], true)) {
            $this->newLine();
            $this->warn('Kuyruk worker / Horizon yeniden başlatılmalı (eski SMTP bilgisi bellekte kalabilir):');
            $this->line('  php artisan horizon:terminate   (Horizon kullanıyorsanız)');
            $this->line('  veya queue worker / supervisor restart');
        }

        return self::SUCCESS;
    }

    private function maskSecret(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '(boş)';
        }

        $string = (string) $value;

        if (strlen($string) <= 4) {
            return '****';
        }

        return substr($string, 0, 2).'****'.substr($string, -2);
    }

    private function maskMailUrl(string $url): string
    {
        return (string) preg_replace('/(:\/\/[^:]+:)[^@]+(@)/', '$1****$2', $url);
    }
}
