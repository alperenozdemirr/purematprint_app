<?php

declare(strict_types=1);

namespace App\Http\Services\Exceptions;

use RuntimeException;

class ExchangeRateUnavailableException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Güncel döviz kuru alınamadı. Yurt dışı ödeme şu an gerçekleştirilemiyor. Lütfen daha sonra tekrar deneyin.');
    }
}
