<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\RajaongkirService;
use App\Services\MidtransService;
use App\Models\Wishlist;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BagStoreController extends Controller
{
    /**
     * Show modern 3D Landing Page
     */
    public function index(Request $request)
    {
        $categories = \App\Models\Category::withCount(['products' => function($q) {
            $q->where('is_active', true);
        }])->get();

        $brands = \App\Models\Brand::withCount(['products' => function($q) {
            $q->where('is_active', true);
        }])->get();

        $query = Product::with(['category', 'brand', 'variants', 'images'])->where('is_active', true);

        // Pencarian Kata Kunci (Nama, Deskripsi, Brand, Material)
        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('material', 'like', "%{$keyword}%")
                  ->orWhereHas('brand', function($b) use ($keyword) {
                      $b->where('name', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('category', function($c) use ($keyword) {
                      $c->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        // Filter Kategori jika ada
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter Brand jika ada
        if ($request->filled('brand')) {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        $products = $query->latest()->get();
        $heroProduct = Product::with(['variants', 'images'])->where('is_featured', true)->first();

        // Build Dynamic JSON-LD Schema for Google Rich Snippets
        $schemaProducts = $products->map(function ($prod) {
            $variant = $prod->variants->first();
            $primaryImage = $prod->images->first()?->image_path ? asset($prod->images->first()->image_path) : ($variant?->image_path ?? asset('models/luxury_bag.glb'));
            
            return [
                '@type' => 'Product',
                'name' => $prod->name,
                'image' => [$primaryImage],
                'description' => strip_tags($prod->description),
                'sku' => $variant?->sku ?? 'MK-' . $prod->id,
                'material' => $prod->material,
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $prod->brand?->name ?? 'Mikael on Shop',
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'url' => url('/#katalog'),
                    'priceCurrency' => 'IDR',
                    'price' => (string) ($variant?->promo_price ?? $variant?->price ?? 349000),
                    'availability' => 'https://schema.org/InStock',
                    'itemCondition' => 'https://schema.org/NewCondition',
                ],
            ];
        });

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Store',
            'name' => 'Mikael on Shop',
            'description' => 'Toko tas wanita & pria mewah direct-to-consumer dengan visual 3D interaktif Three.js dan bahan kulit premium.',
            'url' => url('/'),
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Katalog Tas Kulit Eksklusif',
                'itemListElement' => $schemaProducts,
            ],
        ];

        return view('landing', compact('products', 'heroProduct', 'categories', 'brands', 'jsonLd'));
    }

    /**
     * XML Sitemap for Google Search Console
     */
    public function sitemap()
    {
        $products = Product::where('is_active', true)->latest()->get();

        return response()->view('sitemap', compact('products'))->header('Content-Type', 'text/xml');
    }

    /**
     * Detail Produk Publik (Storefront Detail + Lightbox)
     */
    public function productDetail($slug)
    {
        $product = Product::with(['category', 'brand', 'variants', 'images'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::with(['category', 'variants', 'images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        $isWishlisted = false;
        if (\Illuminate\Support\Facades\Auth::check()) {
            $isWishlisted = Wishlist::where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->where('product_id', $product->id)
                ->exists();
        }

        return view('product-detail', compact('product', 'relatedProducts', 'isWishlisted'));
    }

    /**
     * Cari destinasi Indonesia via RajaOngkir Komerce
     */
    public function searchLocation(Request $request, RajaongkirService $rajaongkir)
    {
        $search = $request->query('q', '');
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $results = $rajaongkir->searchDestination($search);
        return response()->json($results);
    }

    /**
     * Hitung ongkos kirim real-time
     */
    public function calculateShippingCost(Request $request, RajaongkirService $rajaongkir)
    {
        $destinationId = (int)$request->input('destination_id');
        $weight = (int)$request->input('weight', 1000);
        $courier = $request->input('courier', 'jne');

        if (!$destinationId) {
            return response()->json(['error' => 'Destinasi belum dipilih'], 422);
        }

        $costs = $rajaongkir->calculateCost($destinationId, $weight, $courier);
        return response()->json($costs);
    }

    /**
     * Handle Instant 1-Page Direct Checkout (Guest Checkout)
     */
     public function checkout(Request $request, MidtransService $midtrans)
     {
         // Guard: Akun Administrator dilarang checkout barang toko sendiri
         if (Auth::check() && Auth::user()->role === 'admin') {
             return response()->json([
                 'status' => 'error',
                 'message' => 'Akun Administrator tidak diperbolehkan memesan produk toko sendiri. Silakan login menggunakan akun pelanggan/customer.'
             ], 403);
         }

         $validated = $request->validate([
             'variant_id' => 'required|exists:product_variants,id',
             'quantity' => 'required|integer|min:1|max:10',
             'customer_name' => 'required|string|max:100',
             'customer_phone' => 'required|string|max:25',
             'customer_email' => 'nullable|email|max:100',
             'province' => 'required|string|max:100',
             'city' => 'required|string|max:100',
             'district' => 'required|string|max:100',
             'address_line' => 'required|string|max:500',
             'courier' => 'required|string',
             'payment_method' => 'required|in:COD,TRANSFER,QRIS',
             'notes' => 'nullable|string|max:255',
         ]);

         $variant = ProductVariant::with('product')->findOrFail($validated['variant_id']);

         if ($variant->stock < $validated['quantity']) {
             return back()->with('error', "Maaf, stok varian warna {$variant->color_name} tersisa {$variant->stock} pcs.");
         }

         // Shipping cost lookup
         $shippingCosts = [
             'JNE-REG' => ['cost' => 15000, 'code' => 'JNE', 'service' => 'REG (2-3 Hari)'],
             'JNE-YES' => ['cost' => 25000, 'code' => 'JNE', 'service' => 'YES (1 Hari Sampai)'],
             'SICEPAT-REG' => ['cost' => 14000, 'code' => 'SiCepat', 'service' => 'REG (2-3 Hari)'],
             'COD-EXPRESS' => ['cost' => 0, 'code' => 'COD Express', 'service' => 'Bayar di Tempat (Bebas Ongkir)'],
         ];

         $courierData = $shippingCosts[$validated['courier']] ?? ['cost' => 15000, 'code' => 'JNE', 'service' => 'REG (2-3 Hari)'];
         
         // Aturan Khusus COD Mikael on Shop: Bebas Ongkir (Rp 0)
         if ($validated['payment_method'] === 'COD') {
             $shippingCost = 0;
             $courierData['cost'] = 0;
         } else {
             $shippingCost = $courierData['cost'] > 0 ? $courierData['cost'] : 15000;
         }

         $qty = (int)$validated['quantity'];
         $unitPrice = $variant->promo_price ?: $variant->price;
         $subtotal = $unitPrice * $qty;
         $grandTotal = $subtotal + $shippingCost;

         // Eksekusi Pembuatan Order & Potong Stok dengan Pessimistic Database Lock (Anti-Race Condition)
         try {
             $notesText = !empty($validated['notes']) ? " (Catatan: {$validated['notes']})" : "";

             $order = DB::transaction(function () use ($validated, $courierData, $shippingCost, $notesText, $qty, $unitPrice, $subtotal, $grandTotal) {
                 // lockForUpdate mengunci baris stok varian di level database agar tidak tabrakan antar 2 user
                 $lockedVariant = ProductVariant::with('product')
                     ->where('id', $validated['variant_id'])
                     ->lockForUpdate()
                     ->firstOrFail();

                 if ($lockedVariant->stock < $qty) {
                     throw new \Exception("Maaf, stok varian warna {$lockedVariant->color_name} baru saja habis dibeli pelanggan lain!");
                 }

                 $initialStatus = ($validated['payment_method'] === 'COD') ? 'processing' : 'pending';

                 $newOrder = Order::create([
                     'user_id' => Auth::id(),
                     'order_number' => 'MK-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                     'customer_name' => $validated['customer_name'],
                     'customer_phone' => $validated['customer_phone'],
                     'customer_email' => $validated['customer_email'] ?? (Auth::user()?->email ?? null),
                     'address_line' => $validated['address_line'] . $notesText,
                     'province' => $validated['province'],
                     'city' => $validated['city'],
                     'district' => $validated['district'],
                     'courier_code' => $courierData['code'],
                     'courier_service' => $courierData['service'],
                     'shipping_cost' => $shippingCost,
                     'subtotal' => $subtotal,
                     'grand_total' => $grandTotal,
                     'status' => $initialStatus,
                     'payment_method' => $validated['payment_method'],
                     'paid_at' => null,
                     'expires_at' => now()->addMinutes(15), // Kunci slot 15 menit
                 ]);

                 OrderItem::create([
                     'order_id' => $newOrder->id,
                     'variant_id' => $lockedVariant->id,
                     'product_name' => $lockedVariant->product->name,
                     'variant_name' => $lockedVariant->color_name,
                     'unit_price' => $unitPrice,
                     'quantity' => $qty,
                     'total_price' => $subtotal,
                 ]);

                 // Kunci stok aman secara atomik
                 $lockedVariant->decrement('stock', $qty);

                 return $newOrder;
             });
         } catch (\Exception $e) {
             if ($request->ajax() || $request->wantsJson()) {
                 return response()->json(['error' => $e->getMessage()], 422);
             }
             return back()->with('error', $e->getMessage());
         }

         // Jika metode pembayaran menggunakan Midtrans (QRIS / Transfer Bank Digital)
         if (in_array($validated['payment_method'], ['QRIS', 'TRANSFER'])) {
             $snapPayload = [
                 'transaction_details' => [
                     'order_id' => $order->order_number,
                     'gross_amount' => (int) $order->grand_total,
                 ],
                 'customer_details' => [
                     'first_name' => $order->customer_name,
                     'phone' => $order->customer_phone,
                     'email' => $order->customer_email ?: 'customer@mikaelonshop.com',
                 ],
                 'item_details' => [
                     [
                         'id' => 'VAR-' . $variant->id,
                         'price' => (int) $unitPrice,
                         'quantity' => $qty,
                         'name' => substr($variant->product->name . ' - ' . $variant->color_name, 0, 50),
                     ],
                     [
                         'id' => 'SHIPPING',
                         'price' => (int) $shippingCost,
                         'quantity' => 1,
                         'name' => 'Ongkos Kirim ' . $courierData['code'],
                     ]
                 ]
             ];

             $snapRes = $midtrans->createSnapTransaction($snapPayload);
             if ($snapRes && !empty($snapRes['token'])) {
                 if ($request->ajax() || $request->wantsJson()) {
                     return response()->json([
                         'snap_token' => $snapRes['token'],
                         'redirect_url' => $snapRes['redirect_url'] ?? null,
                         'order_number' => $order->order_number,
                         'success_url' => route('order.success', $order->order_number)
                     ]);
                 }
             }
         }

         if ($request->ajax() || $request->wantsJson()) {
             return response()->json([
                 'redirect' => route('order.success', $order->order_number)
             ]);
         }

         return redirect()->route('order.success', $order->order_number);
     }

     /**
      * Halaman Sukses Checkout
      */
     public function orderSuccess($order_number, MidtransService $midtrans)
     {
         $order = Order::with('items.variant.product')->where('order_number', $order_number)->firstOrFail();

         // Cek real-time status Midtrans jika order non-COD belum tercatat payment_channel-nya
         if ($order->payment_method !== 'COD') {
             $txStatus = $midtrans->getTransactionStatus($order->order_number);
             if ($txStatus && !empty($txStatus['transaction_status'])) {
                 $updates = [];
                 $paymentType = $txStatus['payment_type'] ?? '';

                 // Format nama channel ramah
                 $channelLabel = match ($paymentType) {
                     'qris' => 'QRIS (Gopay/OVO/ShopeePay)',
                     'gopay' => 'GoPay / QRIS',
                     'shopeepay' => 'ShopeePay',
                     'bank_transfer' => isset($txStatus['va_numbers'][0]['bank']) 
                         ? 'Virtual Account ' . strtoupper($txStatus['va_numbers'][0]['bank']) 
                         : (isset($txStatus['permata_va_number']) ? 'Virtual Account Permata' : 'Transfer Bank (VA)'),
                     'echannel' => 'Mandiri Bill Payment (VA)',
                     'cstore' => 'Convenience Store (' . ucfirst($txStatus['store'] ?? 'Alfamart/Indomaret') . ')',
                     default => strtoupper(str_replace('_', ' ', $paymentType ?: $order->payment_method))
                 };

                 $updates['payment_channel'] = $channelLabel;
                 $updates['payment_payload'] = json_encode($txStatus);

                 if ($txStatus['transaction_status'] === 'settlement' || ($txStatus['transaction_status'] === 'capture' && ($txStatus['fraud_status'] ?? '') === 'accept')) {
                     $updates['status'] = 'paid';
                     if (!$order->paid_at) {
                         $updates['paid_at'] = now();
                     }
                 }

                 $order->update($updates);
                 $order->refresh();
             }
         }

         return view('order-success', compact('order'));
     }

     /**
      * Stream Barcode QRIS Resmi dari Midtrans Gateway
      */
     public function streamQrisImage($order_number, MidtransService $midtrans)
     {
         $order = Order::where('order_number', $order_number)->firstOrFail();
         $payload = json_decode($order->payment_payload, true);
         $txId = $payload['transaction_id'] ?? null;

         if (!$txId) {
             $txStatus = $midtrans->getTransactionStatus($order->order_number);
             $txId = $txStatus['transaction_id'] ?? null;
         }

         if (!$txId) {
             abort(404, 'Transaksi QRIS belum terbuat di Midtrans');
         }

         $qrUrl = $midtrans->getQrisQrCodeUrl($txId);
         $serverKey = env('MIDTRANS_SERVER_KEY');
         $res = Http::withoutVerifying()
             ->withBasicAuth($serverKey, '')
             ->get($qrUrl);

         if ($res->successful()) {
             return response($res->body())
                 ->header('Content-Type', 'image/png')
                 ->header('Cache-Control', 'no-cache, private');
         }

         abort(502, 'Gagal mengambil gambar QR Code dari Midtrans');
     }

    /**
     * Webhook Notifikasi Pembayaran Resmi dari Midtrans
     */
    public function midtransWebhook(Request $request)
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Order ID missing'], 400);
        }

        $order = Order::where('order_number', $orderId)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Settlement atau capture sukses = Lunas
        $paymentType = $payload['payment_type'] ?? '';
        $channelLabel = match ($paymentType) {
            'qris' => 'QRIS (Gopay/OVO/ShopeePay)',
            'gopay' => 'GoPay / QRIS',
            'shopeepay' => 'ShopeePay',
            'bank_transfer' => isset($payload['va_numbers'][0]['bank']) 
                ? 'Virtual Account ' . strtoupper($payload['va_numbers'][0]['bank']) 
                : (isset($payload['permata_va_number']) ? 'Virtual Account Permata' : 'Transfer Bank (VA)'),
            'echannel' => 'Mandiri Bill Payment (VA)',
            'cstore' => 'Convenience Store (' . ucfirst($payload['store'] ?? 'Alfamart/Indomaret') . ')',
            default => strtoupper(str_replace('_', ' ', $paymentType ?: $order->payment_method))
        };

        $updateFields = [
            'payment_channel' => $channelLabel,
            'payment_payload' => json_encode($payload),
        ];

        if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
            $updateFields['status'] = 'paid';
            $updateFields['paid_at'] = now();
            $order->update($updateFields);
        } elseif ($transactionStatus === 'pending') {
            $updateFields['status'] = 'pending';
            $order->update($updateFields);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $updateFields['status'] = 'cancelled';
            if ($order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('stock', $item->quantity);
                    }
                }
            }
            $order->update($updateFields);
        }

        return response()->json(['message' => 'Webhook handled successfully']);
    }

    /**
     * Tombol Sinkronisasi Status Transaksi Midtrans di Admin Dashboard
     */
    public function syncMidtransStatus(Request $request, $id, MidtransService $midtrans)
    {
        $order = Order::with('items.variant')->findOrFail($id);

        $res = $midtrans->getTransactionStatus($order->order_number);
        if (!$res) {
            return back()->with('error', "Gagal mengambil status dari Midtrans untuk order #{$order->order_number}.");
        }

        $txStatus = $res['transaction_status'] ?? null;
        $fraudStatus = $res['fraud_status'] ?? null;

        if ($txStatus === 'settlement' || ($txStatus === 'capture' && $fraudStatus === 'accept')) {
            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
            return back()->with('success', "Order #{$order->order_number} terverifikasi SETTLEMENT di Midtrans! Status otomatis diubah menjadi SUDAH LUNAS.");
        } elseif ($txStatus === 'pending') {
            return back()->with('info', "Status pesanan #{$order->order_number} masih PENDING di Midtrans (menunggu pembayaran pembeli).");
        } elseif (in_array($txStatus, ['deny', 'expire', 'cancel'])) {
            if ($order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('stock', $item->quantity);
                    }
                }
            }
            $order->update(['status' => 'cancelled']);
            return back()->with('warning', "Status pesanan di Midtrans adalah {$txStatus}. Pesanan dibatalkan & stok dikembalikan.");
        }

        return back()->with('info', "Status Midtrans: " . ($txStatus ?: 'Unknown'));
    }

    /**
     * Admin Order Dashboard
     */
    public function adminDashboard(Request $request)
    {
        $query = Order::with('items')->latest();

        // 1. Pencarian Nomor Order, Nama Pelanggan, Nomor HP, atau Nomor Resi
        if ($request->filled('q')) {
            $keyword = trim($request->query('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('order_number', 'like', "%{$keyword}%")
                  ->orWhere('customer_name', 'like', "%{$keyword}%")
                  ->orWhere('customer_phone', 'like', "%{$keyword}%")
                  ->orWhere('tracking_number', 'like', "%{$keyword}%")
                  ->orWhere('city', 'like', "%{$keyword}%");
            });
        }

        // 2. Filter Status Pesanan (pending, paid, processing, shipped, completed, cancelled)
        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        // 3. Filter Metode Pembayaran (COD, TRANSFER, QRIS)
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->query('payment_method'));
        }

        // Stat counter global (tidak terpotong filter pencarian)
        $totalOrdersCount = Order::count();
        $readyOrdersCount = Order::whereIn('status', ['paid', 'processing'])->count();
        $shippedOrdersCount = Order::where('status', 'shipped')->count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $totalRevenue = Order::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])->sum('grand_total');

        // 4. Pagination 10 pesanan per halaman
        $orders = $query->paginate(10)->withQueryString();

        return view('admin.dashboard', compact(
            'orders', 
            'totalRevenue',
            'totalOrdersCount',
            'readyOrdersCount',
            'shippedOrdersCount',
            'pendingOrdersCount'
        ));
    }

    /**
     * Update Tracking Resi & Input Manual/Auto Resi
     */
    public function shipOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Validasi Status Pembayaran: Non-COD yang masih pending tidak boleh dikirim
        if ($order->payment_method !== 'COD' && $order->status === 'pending') {
            return redirect()->route('admin.dashboard')->with('error', 'Barang belum dibayar! Pembayaran Midtrans masih berstatus pending.');
        }

        $trackingNumber = $request->input('tracking_number');

        if (empty($trackingNumber)) {
            $prefix = strtoupper(preg_replace('/[^A-Z]/', '', $order->courier_code ?: 'JNE'));
            $prefix = substr($prefix, 0, 4) ?: 'EXP';
            $trackingNumber = $prefix . date('Ymd') . rand(100000, 999999);
        }

        $order->update([
            'status' => 'shipped',
            'tracking_number' => $trackingNumber,
        ]);

        return redirect()->route('admin.dashboard')->with('success', "Order #{$order->order_number} berhasil dikirim dengan nomor resi {$order->tracking_number}.");
    }

    /**
     * Update Cepat Status Pesanan (Fulfillment Gudang)
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::with('items.variant')->findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,completed,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        $updateData = ['status' => $newStatus];
        if ($newStatus === 'paid' && !$order->paid_at) {
            $updateData['paid_at'] = now();
        }

        // Auto-Restock: Jika pesanan dibatalkan (cancelled), kembalikan stok varian tas
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->variant) {
                    $item->variant->increment('stock', $item->quantity);
                }
            }
        }
        // Jika pesanan yang tadinya cancelled diaktifkan kembali
        elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->variant) {
                    $item->variant->decrement('stock', $item->quantity);
                }
            }
        }

        $order->update($updateData);

        return redirect()->route('admin.dashboard')->with('success', "Status pesanan #{$order->order_number} berhasil diperbarui menjadi " . strtoupper($newStatus) . ".");
    }

    /**
     * Detail Pesanan Admin Panel
     */
    public function adminOrderShow($id)
    {
        $order = Order::with(['items.variant.product', 'user'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Cetak Invoice Resmi Toko (Printable A4 / PDF)
     */
    public function adminOrderInvoice($id)
    {
        $order = Order::with(['items.variant.product', 'user'])->findOrFail($id);
        return view('admin.orders.invoice', compact('order'));
    }

    /**
     * Cetak Label Resi Pengiriman Thermal Gudang (10x15 cm)
     */
    public function printThermalLabel($id)
    {
        $order = Order::with('items.variant.product')->findOrFail($id);
        return view('admin.orders.thermal-label', compact('order'));
    }
}
