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

        $brandName = $product->brand ? $product->brand->name : 'Mikael On Shop';
        $categoryName = $product->category ? $product->category->name : 'Luxury Bag';

        // Susun daftar varian warna & harga promo/normal
        $variantList = $product->variants->map(function ($v) {
            $priceText = $v->promo_price 
                ? 'Rp ' . number_format($v->promo_price, 0, ',', '.') . ' (Normal: Rp ' . number_format($v->price, 0, ',', '.') . ')'
                : 'Rp ' . number_format($v->price, 0, ',', '.');
            return "  • Varian {$v->color_name}: {$priceText} | Stok: {$v->stock} pcs";
        })->implode("\n");

        // WhatsApp CS Toko Resmi
        $waAdminNumber = '6281288992211';
        $waText = "Halo Mikael On Shop, saya tertarik memesan tas {$product->name} ({$productUrl})";
        $waLink = "https://wa.me/{$waAdminNumber}?text=" . urlencode($waText);

        // Susun teks caption lengkap dan profesional untuk Facebook Toko
        $message = $customMessage ?: "✨ KOLEKSI TERBARU RESMI: {$product->name} ✨\n\n"
            . "🏷️ Brand: {$brandName}\n"
            . "📂 Kategori: {$categoryName}\n"
            . "🧵 Bahan/Material: {$product->material}\n"
            . "📐 Dimensi/Ukuran: {$product->dimensions_cm}\n"
            . "💎 Harga Mulai: {$formattedPrice}\n\n"
            . "🎨 PILIHAN WARNA & STOK GUDANG:\n"
            . ($variantList ?: "  • Stok Ready Siap Kirim\n") . "\n"
            . "🛡️ 100% Produk Original & Bergaransi\n"
            . "🚚 Mendukung Bayar di Tempat (COD) Seluruh Indonesia\n\n"
            . "🌐 Link Produk di Website:\n{$productUrl}\n\n"
            . "📱 Pesan Cepat via WhatsApp Official:\n{$waLink}\n\n"
            . "#MikaelOnShop #TasWanita #TasLuxury #OriginalBag #FashionIndonesia #JualTasOriginal #COD";

        // Cari file fisik foto utama produk di direktori storage lokal
        $localImagePath = null;
        if ($primaryImg && $primaryImg->image_path) {
            $candidatePath = public_path($primaryImg->image_path);
            if (file_exists($candidatePath)) {
                $localImagePath = $candidatePath;
            } else {
                $storagePath = storage_path('app/public/' . str_replace('storage/', '', $primaryImg->image_path));
                if (file_exists($storagePath)) {
                    $localImagePath = $storagePath;
                }
            }
        }

        try {
            // JIKA ADA FILE FOTO ASLI: Upload langsung ke endpoint /{page-id}/photos agar gambar asli muncul di FB
            if ($localImagePath && file_exists($localImagePath)) {
                $endpoint = "https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/photos";

                $response = Http::timeout(45)->attach(
                    'source',
                    file_get_contents($localImagePath),
                    basename($localImagePath)
                )->post($endpoint, [
                    'message' => $message,
                    'access_token' => $this->pageAccessToken,
                ]);
            } else {
                $endpoint = "https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/feed";

                $payload = [
                    'message' => $message,
                    'access_token' => $this->pageAccessToken,
                ];

                if (!str_contains($productUrl, 'localhost') && !str_contains($productUrl, '127.0.0.1')) {
                    $payload['link'] = $productUrl;
                }

                $response = Http::timeout(30)->post($endpoint, $payload);
            }

            if ($response->successful()) {
                $data = $response->json();
                $postId = $data['post_id'] ?? $data['id'] ?? null;
                Log::info("Facebook Post Success for Product #{$product->id}: Post ID {$postId}");
                return [
                    'success' => true,
                    'post_id' => $postId,
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
