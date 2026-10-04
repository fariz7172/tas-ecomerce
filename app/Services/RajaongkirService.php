<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaongkirService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://rajaongkir.komerce.id/api/v1';
    protected int $defaultOriginId;

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY', 'tAy2KSuL7b58ca53a06fc881WRj6BL7f');
        // Default Gudang: Grogol, Jakarta Barat (17473)
        $this->defaultOriginId = (int) env('RAJAONGKIR_ORIGIN_ID', 17473);
    }

    /**
     * Cari destinasi domestik berdasarkan kata kunci (Kelurahan / Kecamatan / Kota)
     */
    public function searchDestination(string $search): array
    {
        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'key' => $this->apiKey,
                ])
                ->get("{$this->baseUrl}/destination/domestic-destination", [
                    'search' => $search,
                ]);

            if ($response->successful()) {
                return $response->json('data') ?? [];
            }
        } catch (\Throwable $e) {
            Log::error('Rajaongkir searchDestination error: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Hitung ongkos kirim real-time
     */
    public function calculateCost(int $destinationId, int $weight = 1000, string $courier = 'jne'): array
    {
        try {
            $response = Http::withoutVerifying()
                ->asForm()
                ->withHeaders([
                    'key' => $this->apiKey,
                ])
                ->post("{$this->baseUrl}/calculate/domestic-cost", [
                    'origin' => $this->defaultOriginId,
                    'destination' => $destinationId,
                    'weight' => $weight,
                    'courier' => strtolower($courier),
                ]);

            if ($response->successful()) {
                return $response->json('data') ?? [];
            }
        } catch (\Throwable $e) {
            Log::error('Rajaongkir calculateCost error: ' . $e->getMessage());
        }

        return [];
    }
}
