<?php

return [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'currency' => env('STRIPE_CURRENCY', 'try'),
    'international_currency' => env('STRIPE_INTERNATIONAL_CURRENCY', 'eur'),
    'success_url' => env('STRIPE_SUCCESS_URL'),
    'cancel_url' => env('STRIPE_CANCEL_URL'),
];
