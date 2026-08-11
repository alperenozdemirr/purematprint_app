<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$views = [
    'mail.welcome-user' => ['name' => 'Test', 'shippingPromoSentence' => null],
    'mail.verify-email' => ['code' => '123456'],
    'mail.test-queue' => ['message' => 'test'],
    'mail.reset-password' => ['url' => 'https://example.com/reset', 'count' => 60],
    'mail.password-changed' => ['name' => 'Test'],
    'mail.newsletter-broadcast' => ['mailSubject' => 'Test', 'bodyHtml' => '<p>Hi</p>'],
];

$failed = 0;

foreach ($views as $view => $data) {
    try {
        view($view, $data)->render();
        echo "OK: {$view}\n";
    } catch (Throwable $e) {
        $failed++;
        echo "FAIL: {$view} - {$e->getMessage()}\n";
    }
}

exit($failed > 0 ? 1 : 0);
