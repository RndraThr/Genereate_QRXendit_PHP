<?php

namespace App\Controllers;

use App\Models\WebhookModel;
use Exception;

class XenditQRController

{
    private $apiKey;
    private $webhookToken;
    private $baseUrl = 'https://api.xendit.co/';

    public function __construct($apiKey, $webhookToken)
    {
        $this->apiKey = $apiKey;
        $this->webhookToken = $webhookToken;
    }
    public function generateQR($data)
    {
        try {
            $headers = [
                'Authorization: Basic ' . base64_encode($this->apiKey . ':'),
                'Content-Type: application/json',
                'api-version: 2022-07-31'
            ];

            $ch = curl_init($this->baseUrl . '/qr_codes');
            curl_setopt_array($ch, [
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_RETURNTRANSFER => true
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return json_decode($response, true);
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        }
    }

    public function handleCallback($headers, $payload)
    {
        try {
            $receivedToken = null;
            foreach ($headers as $key => $value) {
                $headerKey = strtolower($key);
                if ($headerKey === 'x-callback-token') {
                    $receivedToken = $value;
                    break;
                }
            }
            if (!$receivedToken) {
                throw new Exception('Callback token not found in headers', 401);
            }
            if (trim($receivedToken) !== trim($this->webhookToken)) {
                throw new Exception('Callback token mismatch', 401);
            }
            $data = json_decode($payload, true);
            if (!$data) {
                throw new Exception('Invalid payload format', 400);
            }
            return [
                'success' => true,
                'message' => 'Callback processed successfully',
                'data' => $data
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        }
    }
}
