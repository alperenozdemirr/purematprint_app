<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Support\MailUrl;
use App\Support\MailBranding;

config([
    'app.url' => 'http://localhost',
    'mail.app_url' => 'https://purematprint.com',
]);

echo MailUrl::route('password.reset', ['token' => 'abc', 'email' => 't@test.com']).PHP_EOL;
echo MailBranding::logoUrl().PHP_EOL;
echo MailUrl::rewriteHost('http://localhost/media/shared_directory/logo.png').PHP_EOL;
