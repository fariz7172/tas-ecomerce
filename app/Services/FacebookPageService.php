<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookPageService
{
    protected ?string $pageId;
    protected ?string $pageAccessToken;
    protected string $apiVersion = 'v20.0';

    public function __construct()
    {
        $this->pageId = config('services.facebook.page_id') ?: env('FACEBOOK_PAGE_ID');
        $this->pageAccessToken = config('services.facebook.page_access_token') ?: env('FACEBOOK_PAGE_ACCESS_TOKEN');
    }

    /**
     * Cek apakah kredensial token dan page ID sudah dikonfigurasi di .env
     */
    public function isConfigured(): bool
    {
        return !empty($this->pageId) && !empty($this->pageAccessToken);
    }

    /**
     * Publikasi produk tas ke Wall/Feed Facebook Page resmi toko
     */
    public function publishProduct(Product $product, ?string $customMessage = null): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'error' => 'Kredensial FACEBOOK_PAGE_ID dan FACEBOOK_PAGE_ACCESS_TOKEN belum diisi di file .env server.',
            ];
        }

        $productUrl = url('/product/' . $product->slug);
        
        // Ambil foto utama tas
        $primaryImg = $product->images->first();
        $imageUrl = null;
        if ($primaryImg) {
            $imageUrl = str_starts_with($primaryImg->image_path, 'http')
                ? $primaryImg->image_path
                : asset($primaryImg->image_path);
        }

        // Ambil rentang harga varian
        $minPrice = $product->variants->min('promo_price') ?: $product->variants->min('price');
        $formattedPrice = $minPrice ? 'Rp ' . number_format($minPrice, 0, ',', '.') : 'Hubungi Admin';

        // Susun teks caption menarik untuk Facebook
        $message = $customMessage ?: "👜 KOLEKSI TERBARU: {$product->name}\n\n"
            . "✨ Material: {$product->material}\n"
            . "📐 Dimensi: {$product->dimensions_cm}\n"
            . "💎 Harga Mulai: {$formattedPrice}\n"
            . "🔒 Garansi 100% Original & Mendukung COD Se-Indonesia\n\n"
            . "Lihat detail & pesan sekarang:\n{$productUrl}\n\n"
            . "#MikaelOnShop #LuxuryBag #TasWanita #OriginalLeather #FashionID";

        try {
            // Jika ada foto dan URL bukan localhost (misal domain live), kita bisa post photo with caption
            // Namun endpoint /photos memerlukan foto publik atau file upload.
            // Endpoint /{page-id}/feed paling fleksibel dengan melampirkan link produk.
            $endpoint = "https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/feed";

            $payload = [
                'message' => $message,
                'link' => $productUrl,
                'access_token' => $this->pageAccessToken,
            ];

            $response = Http::timeout(30)->post($endpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("Facebook Post Success for Product #{$product->id}: Post ID {$data['id']}");
                return [
                    'success' => true,
                    'post_id' => $data['id'] ?? null,
                    'message' => 'Produk tas berhasil dipublikasikan ke Facebook Page resmi toko!',
                ];
            }

            $errorData = $response->json();
            $errorMessage = $errorData['error']['message'] ?? 'Terjadi kesalahan saat memposting ke Facebook Graph API.';
            Log::error("Facebook Post Failed: " . json_encode($errorData));

            return [
                'success' => false,
                'error' => "Facebook API Error: {$errorMessage}",
            ];

        } catch (\Exception $e) {
            Log::error("Facebook Post Exception: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Koneksi ke Facebook API gagal: ' . $e->getMessage(),
            ];
        }
    }
}
