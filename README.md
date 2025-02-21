# Xendit QR Implementation

Implementasi PHP untuk Xendit QR Code generation dan payment callback handling.

## Instalasi

1. Clone repository ini
2. Install dependencies:
   ```bash
   composer require vlucas/phpdotenv
   ```

3. Copy `.env.example` ke `.env` dan sesuaikan konfigurasi:
   ```
   DB_HOST=localhost
   DB_NAME=xendit_qr
   DB_USER=root
   DB_PASS=

   XENDIT_API_KEY=your_api_key
   XENDIT_WEBHOOK_TOKEN=your_webhook_token
   ```

4. Buat database MySQL:
   ```sql
   CREATE DATABASE xendit_qr;
   ```

5. Tabel akan dibuat otomatis saat aplikasi pertama kali dijalankan

## Penggunaan

### Generate QR Code

```bash
curl -X POST http://your-domain.com/generate-qr \
  -H "Content-Type: application/json" \
  -d '{
    "reference_id": "order-123",
    "type": "DYNAMIC",
    "currency": "IDR",
    "amount": 10000
  }'
```

### Callback URL

Set callback URL di dashboard Xendit ke:
```
http://your-domain.com/xendit-callback
```

## Keamanan

- Pastikan `.env` tidak masuk ke version control
- Gunakan HTTPS untuk production
- Validasi semua input
- Simpan log untuk debugging

## Testing

1. Generate QR code
2. Scan QR dengan aplikasi pembayaran
3. Lakukan pembayaran
4. Cek callback diterima dan diproses