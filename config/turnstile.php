<?php

declare(strict_types=1);

return [
    'enabled' => filter_var(env('TURNSTILE_ENABLED', true), FILTER_VALIDATE_BOOL),

    'site_key' => env('TURNSTILE_SITE_KEY'),

    'secret_key' => env('TURNSTILE_SECRET_KEY'),
];
