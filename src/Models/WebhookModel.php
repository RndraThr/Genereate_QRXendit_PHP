<?php

namespace App\Models;

use App\Database\Connection;
use PDO;

class WebhookModel
{
    private $db;

    public function __construct()
    {
        try {
            $this->db = Connection::getInstance()->getConnection();
        } catch (\Exception $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function createWebhooksTable()
    {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS webhooks (
                id INT AUTO_INCREMENT PRIMARY KEY,
                webhook_id VARCHAR(255) NOT NULL UNIQUE,
                payment_data JSON,
                processed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

            $this->db->exec($sql);
            return true;
        } catch (\Exception $e) {
            die("Table creation failed: " . $e->getMessage());
        }
    }

    public function isProcessed($webhookId)
    {
        try {
            $stmt = $this->db->prepare("SELECT id FROM webhooks WHERE webhook_id = ?");
            $stmt->execute([$webhookId]);
            return $stmt->rowCount() > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function markAsProcessed($webhookId, $paymentData)
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO webhooks (webhook_id, payment_data, processed_at) 
                VALUES (?, ?, NOW())"
            );
            return $stmt->execute([
                $webhookId,
                json_encode($paymentData)
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
