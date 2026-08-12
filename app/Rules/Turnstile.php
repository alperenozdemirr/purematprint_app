<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Throwable;

class Turnstile implements ValidationRule
{
    public function __construct(
        private readonly ?string $secretKey = null,
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = $this->secretKey ?? config('turnstile.secret_key');

        if (! $this->isEnabled($secret)) {
            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('Güvenlik doğrulamasını tamamlayın.');

            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => $secret,
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);
        } catch (Throwable) {
            $fail('Güvenlik doğrulaması şu an yapılamıyor. Lütfen tekrar deneyin.');

            return;
        }

        if (! $response->successful() || ! $response->json('success')) {
            $fail('Güvenlik doğrulaması başarısız. Lütfen tekrar deneyin.');
        }
    }

    private function isEnabled(?string $secret): bool
    {
        return config('turnstile.enabled') && filled($secret);
    }
}
