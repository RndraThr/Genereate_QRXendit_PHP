<?php

$host = 'localhost';
$port = 8000;
$public = __DIR__ . '/public';

echo "====================================" . PHP_EOL;
echo "Xendit QR PHP Development Server" . PHP_EOL;
echo "====================================" . PHP_EOL;
echo "Server started at: http://{$host}:{$port}" . PHP_EOL;
echo "Document root: {$public}" . PHP_EOL;
echo "Press Ctrl+C to stop the server" . PHP_EOL;
echo "====================================" . PHP_EOL;

$command = sprintf(
    'php -S %s:%d -t %s',
    $host,
    $port,
    escapeshellarg($public)
);

// Start the server
system($command);
