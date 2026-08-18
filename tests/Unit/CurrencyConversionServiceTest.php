<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Http\Services\CurrencyConversionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CurrencyConversionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::forget('fx_eur_try_rate');
    }

    public function test_try_to_eur_uses_tcmb_eur_selling_rate(): void
    {
        Http::fake([
            'https://www.tcmb.gov.tr/kurlar/today.xml' => Http::response(
                '<?xml version="1.0"?><Tarih_Date><Currency CurrencyCode="EUR"><ForexSelling>40,0000</ForexSelling></Currency></Tarih_Date>',
                200,
                ['Content-Type' => 'application/xml']
            ),
        ]);

        config(['stripe.eur_to_try_rate' => null]);

        $service = app(CurrencyConversionService::class);

        $this->assertSame(40.0, $service->eurToTry());
        $this->assertSame(32.48, $service->tryToEur(1299.0));
    }

    public function test_implied_rate_matches_paid_amount(): void
    {
        $service = app(CurrencyConversionService::class);

        $this->assertSame(0.025404, $service->impliedEurPerTry(1299.0, 33.0));
    }

    public function test_exchange_rate_unavailable_when_sources_fail(): void
    {
        Http::fake([
            'https://www.tcmb.gov.tr/kurlar/today.xml' => Http::response('', 503),
            'https://open.er-api.com/v6/latest/EUR' => Http::response('', 503),
        ]);

        $service = app(CurrencyConversionService::class);

        $this->expectException(\App\Http\Services\Exceptions\ExchangeRateUnavailableException::class);

        $service->eurToTry();
    }
}
