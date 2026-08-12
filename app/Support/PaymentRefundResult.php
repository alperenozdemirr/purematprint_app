<?php

declare(strict_types=1);

namespace App\Support;

final class PaymentRefundResult
{
    public function __construct(
        public bool $success,
        public ?string $message = null,
        public ?string $method = null,
    ) {
    }

    public static function ok(string $method, ?string $message = null): self
    {
        return new self(true, $message, $method);
    }

    public static function fail(string $message): self
    {
        return new self(false, $message);
    }
}
