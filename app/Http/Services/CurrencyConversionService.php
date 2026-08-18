<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Http\Services\Exceptions\ExchangeRateUnavailableException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyConversionService
{
    public function eurToTry(): float
    {
        return Cache::remember('fx_eur_try_rate', now()->addHour(), function (): float {
            $tcmbRate = $this->fetchTcmbEurToTryRate();
            if ($tcmbRate !== null) {
                return $tcmbRate;
            }

            $apiRate = $this->fetchOpenErApiEurToTryRate();
            if ($apiRate !== null) {
                return $apiRate;
            }

            throw new ExchangeRateUnavailableException();
        });
    }

    public function eurPerTry(): float
    {
        $eurToTry = $this->eurToTry();

        return round(1 / $eurToTry, 6);
    }

    public function tryToEur(float $tryAmount): float
    {
        $eurToTry = $this->eurToTry();

        return round(max(0, $tryAmount) / $eurToTry, 2);
    }

    /**
     * Stripe satır kalemleri ile aynı kuruş yuvarlamasını uygular.
     */
    public function quoteCartEurTotal(iterable $cartItems, array $summary, callable $unitPriceForItem): float
    {
        $eurPerTry = $this->eurPerTry();
        $subtotal = (float) ($summary['subtotal'] ?? 0);
        $discountFactor = $subtotal > 0 && ($summary['discountApplied'] ?? false)
            ? max(0, ($subtotal - (float) ($summary['discountAmount'] ?? 0)) / $subtotal)
            : 1.0;

        $totalCents = 0;

        foreach ($cartItems as $item) {
            $tryUnitPrice = (float) $unitPriceForItem($item) * $discountFactor;
            $unitCents = (int) round($tryUnitPrice * $eurPerTry * 100);
            $totalCents += max($unitCents, 0) * (int) ($item->quantity ?? 1);
        }

        if (! ($summary['shippingFree'] ?? true) && (float) ($summary['shippingCost'] ?? 0) > 0) {
            $shippingCents = (int) round((float) $summary['shippingCost'] * $eurPerTry * 100);
            $totalCents += max($shippingCents, 0);
        }

        return round($totalCents / 100, 2);
    }

    public function impliedEurPerTry(float $tryAmount, float $eurAmount): ?float
    {
        if ($tryAmount <= 0 || $eurAmount <= 0) {
            return null;
        }

        return round($eurAmount / $tryAmount, 6);
    }

    private function fetchTcmbEurToTryRate(): ?float
    {
        try {
            $response = Http::timeout(5)->get('https://www.tcmb.gov.tr/kurlar/today.xml');

            if (! $response->successful()) {
                return null;
            }

            $xml = @simplexml_load_string($response->body());

            if ($xml === false) {
                return null;
            }

            foreach ($xml->Currency as $currency) {
                if ((string) ($currency['CurrencyCode'] ?? '') !== 'EUR') {
                    continue;
                }

                $rate = $this->parseRateString((string) ($currency->ForexSelling ?? ''));

                if ($rate === null) {
                    $rate = $this->parseRateString((string) ($currency->BanknoteSelling ?? ''));
                }

                if ($rate !== null && $rate > 0) {
                    return round($rate, 4);
                }
            }
        } catch (\Throwable $exception) {
            report($exception);
        }

        return null;
    }

    private function fetchOpenErApiEurToTryRate(): ?float
    {
        try {
            $response = Http::timeout(5)->get('https://open.er-api.com/v6/latest/EUR');

            if (! $response->successful()) {
                return null;
            }

            $rate = $response->json('rates.TRY');

            if (is_numeric($rate) && (float) $rate > 0) {
                return round((float) $rate, 4);
            }
        } catch (\Throwable $exception) {
            report($exception);
        }

        return null;
    }

    private function parseRateString(string $value): ?float
    {
        $normalized = str_replace(',', '.', trim($value));

        if ($normalized === '' || ! is_numeric($normalized)) {
            return null;
        }

        $rate = (float) $normalized;

        return $rate > 0 ? $rate : null;
    }
}
