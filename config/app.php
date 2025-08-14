<?php

return [
    'name' => $_ENV['APP_NAME'] ?? "Default App Name",
    'debug' => $_ENV['APP_DEBUG'] ?? false,
    'env' => $_ENV['APP_ENV'] ?? 'production',
];
