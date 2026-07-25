<?php

return [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'currency' => env('STRIPE_CURRENCY', 'try'),
    'success_url' => env('STRIPE_SUCCESS_URL'),
    'cancel_url' => env('STRIPE_CANCEL_URL'),
];
