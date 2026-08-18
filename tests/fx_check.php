<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$eurBase = Illuminate\Support\Facades\Http::get('https://open.er-api.com/v6/latest/EUR')->json();
$tryBase = Illuminate\Support\Facades\Http::get('https://open.er-api.com/v6/latest/TRY')->json();

echo 'EUR->TRY: '.($eurBase['rates']['TRY'] ?? '?').PHP_EOL;
echo 'TRY->EUR: '.($tryBase['rates']['EUR'] ?? '?').PHP_EOL;
echo 'Reciprocal check: '.(1 / (float) ($eurBase['rates']['TRY'] ?? 1)).PHP_EOL;

$tryTotal = 1299.0;
$rate = app(App\Http\Services\CurrencyConversionService::class)->eurPerTry();
echo 'Service rate: '.$rate.PHP_EOL;
echo '1299 TRY -> '.app(App\Http\Services\CurrencyConversionService::class)->tryToEur($tryTotal).' EUR'.PHP_EOL;
echo '1299 TRY / EURTRY: '.($tryTotal / (float) ($eurBase['rates']['TRY'] ?? 1)).' EUR'.PHP_EOL;
