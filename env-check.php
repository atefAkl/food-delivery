<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

echo "SESSION_DRIVER: " . config('session.driver') . "\n";
echo "SESSION_LIFETIME: " . config('session.lifetime') . "\n";
echo "APP_KEY set: " . (!empty(config('app.key')) ? "Yes" : "No") . "\n";
