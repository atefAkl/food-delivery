<?php

echo "Session Driver: " . env('SESSION_DRIVER', 'default') . "\n";
echo "Session Lifetime: " . env('SESSION_LIFETIME', 'default') . "\n";
echo "APP_KEY: " . (env('APP_KEY') ? "Set" : "Not Set") . "\n";
echo "APP_DEBUG: " . (env('APP_DEBUG') ? "True" : "False") . "\n";
