<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Tampilkan Halaman Checkout Pesanan (Auth Protected)
     */
    public function index(Request $request)
    {
        // Guard: Akun Administrator dilarang checkout barang toko sendiri
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akun Administrator tidak dapat memesan barang. Silakan gunakan akun pembeli/pelanggan.');
        }

        $productId = $request->query('product_id');
        $variantId = $request->query('variant_id');
        $quantity = max(1, (int)$request->query('quantity', 1));

        if ($productId) {
            $product = Product::with(['category', 'brand', 'variants', 'images'])->findOrFail($productId);
        } else {
            $product = Product::with(['category', 'brand', 'variants', 'images'])->where('is_active', true)->firstOrFail();
        }

        $currentVariant = $variantId 
            ? $product->variants->firstWhere('id', $variantId) 
            : $product->variants->first();

        if (!$currentVariant && $product->variants->isNotEmpty()) {
            $currentVariant = $product->variants->first();
        }

        $selectedVariantId = $currentVariant?->id;
        $selectedQty = $quantity;
        $promoPrice = $currentVariant?->promo_price ?: ($currentVariant?->price ?? 0);

        return view('checkout.index', compact('product', 'currentVariant', 'selectedVariantId', 'selectedQty', 'promoPrice'));
    }
}
