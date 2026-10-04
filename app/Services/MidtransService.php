<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected string $merchantId;
    protected bool $isProduction;
    protected string $snapUrl;

    public function __construct()
    {
        $this->serverKey = env('MIDTRANS_SERVER_KEY', '');
        $this->clientKey = env('MIDTRANS_CLIENT_KEY', '');
        $this->merchantId = env('MIDTRANS_MERCHANT_ID', '');
        $this->isProduction = filter_var(env('MIDTRANS_IS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN);

        $this->snapUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    public function isProduction(): bool
    {
        return $this->isProduction;
    }

    /**
     * Cek status transaksi langsung ke API Midtrans
     */
    public function getTransactionStatus(string $orderId): ?array
    {
        try {
            $baseUrl = $this->isProduction
                ? 'https://api.midtrans.com/v2'
                : 'https://api.sandbox.midtrans.com/v2';

            $authHeader = 'Basic ' . base64_encode($this->serverKey . ':');

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => $authHeader,
                    'Accept' => 'application/json',
                ])
                ->get("{$baseUrl}/{$orderId}/status");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("Midtrans getStatus error for {$orderId}: " . $response->body());
        } catch (\Throwable $e) {
            Log::error("Midtrans getStatus exception: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Dapatkan URL gambar QR code resmi untuk transaksi QRIS
     */
    public function getQrisQrCodeUrl(string $transactionId): string
    {
        $baseUrl = $this->isProduction
            ? 'https://api.midtrans.com/v2/qris'
            : 'https://api.sandbox.midtrans.com/v2/qris';

        return "{$baseUrl}/{$transactionId}/qr-code";
    }

    /**
     * Buat Snap Token transaksi Midtrans
     */
    public function createSnapTransaction(array $params): ?array
    {
        try {
            $authHeader = 'Basic ' . base64_encode($this->serverKey . ':');

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => $authHeader,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->snapUrl, $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Midtrans Snap Error: ' . $response->body());
        } catch (\Throwable $e) {
            Log::error('Midtrans Snap Exception: ' . $e->getMessage());
        }

        return null;
    }
}
