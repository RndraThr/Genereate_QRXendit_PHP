<?php

use App\Models\WebhookModel;
use App\Controllers\XenditQRController;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$route = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, x-callback-token, webhook-id');

if ($method === 'OPTIONS') {
    exit(0);
}
$xenditController = new XenditQRController(
    $_ENV['XENDIT_API_KEY'] ?? null,
    $_ENV['XENDIT_WEBHOOK_TOKEN'] ?? null
);

if ($method === 'POST' && $route === '/generate-qr') {
    header('Content-Type: application/json');

    $requestData = json_decode(file_get_contents('php://input'), true);
    $response = $xenditController->generateQR($requestData);

    echo json_encode($response);
    exit;
}

if ($method === 'POST' && $route === '/xendit-callback') {
    header('Content-Type: application/json');

    $headers = array_change_key_case(getallheaders(), CASE_LOWER);
    $payload = file_get_contents('php://input');
    $response = $xenditController->handleCallback($headers, $payload);

    // Perbaikan pengecekan error
    if (isset($response['error']) && $response['error']) {
        http_response_code($response['code'] ?? 400);
    } else {
        http_response_code(200);
    }

    echo json_encode($response);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Not Found']);
