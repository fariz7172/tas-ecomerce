<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }} - Mikael On Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8F9FA;
            color: #1A1A1A;
        }
        .font-serif {
            font-family: 'Playfair Display', serif;
        }
        @media print {
            body {
                background-color: #FFFFFF !important;
                color: #000000 !important;
            }
            .no-print {
                display: none !important;
            }
            .print-border {
                border-color: #E5E7EB !important;
            }
            .page-break-inside-avoid {
                page-break-inside: avoid;
            }
            @page {
                size: A4;
                margin: 12mm 15mm 15mm 15mm;
            }
        }
    </style>
</head>
<body class="p-4 sm:p-8 md:p-12">

    <!-- Top Action Bar (Disembunyikan saat cetak printer) -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('admin.order.show', $order->id) }}" class="text-xs font-bold text-gray-600 hover:text-gray-900 inline-flex items-center gap-1.5 transition-colors">
            <span>← Kembali ke Detail Order</span>
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-gray-900 hover:bg-black text-white text-xs font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                <span>🖨️ Cetak Invoice (A4 / PDF)</span>
            </button>
        </div>
    </div>

    <!-- Container Utama Invoice Resmi -->
    <div class="max-w-4xl mx-auto bg-white rounded-3xl p-8 sm:p-12 border border-gray-200 shadow-sm print-border">
        
        <!-- Header Invoice & Toko -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 border-b border-gray-200 pb-8">
            <div class="flex items-center gap-4">
                <img src="{{ asset('assets/logo.png') }}" alt="Mikael On Shop" class="w-16 h-16 object-contain">
                <div>
                    <h1 class="font-serif text-2xl sm:text-3xl font-bold tracking-tight text-gray-950">Mikael On Shop</h1>
                    <p class="text-xs uppercase tracking-widest text-gray-500 font-semibold mt-0.5">Luxury Handbag & Leather Goods</p>
                    <p class="text-[11px] text-gray-400 mt-1">Jakarta, Indonesia • support@mikaelonshop.id</p>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <span class="text-xs font-bold uppercase tracking-wider text-rose-600 bg-rose-50 px-3 py-1 rounded-full border border-rose-200 inline-block mb-1">
                    Official Tax Invoice
                </span>
                <div class="font-mono text-xl sm:text-2xl font-black text-gray-900">#{{ $order->order_number }}</div>
                <div class="text-xs text-gray-500 mt-0.5">
                    Tanggal: <strong class="text-gray-800">{{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</strong>
                </div>
            </div>
        </div>

        <!-- Meta Billing & Shipping Information -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 py-8 border-b border-gray-200 text-xs">
            <!-- Ditagihkan Kepada -->
            <div class="space-y-1.5">
                <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Ditagihkan Kepada:</span>
                <div class="font-bold text-gray-950 text-sm">{{ $order->customer_name }}</div>
                <div class="text-gray-600 font-mono">{{ $order->customer_phone }}</div>
                @if($order->customer_email)
                <div class="text-gray-600">{{ $order->customer_email }}</div>
                @endif
                <div class="text-gray-700 leading-relaxed pt-1">
                    {{ $order->address_line }}<br>
                    {{ $order->district }}, {{ $order->city }}<br>
                    {{ $order->province }}
                </div>
            </div>

            <!-- Detail Pembayaran & Pengiriman -->
            <div class="space-y-3 sm:text-right">
                <div>
                    <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Metode Pembayaran:</span>
                    <strong class="font-mono font-bold text-gray-900 text-sm">{{ $order->payment_method }}</strong>
                    @if($order->payment_channel)
                    <span class="text-gray-500 text-[11px]">({{ $order->payment_channel }})</span>
                    @endif
                </div>

                <div>
                    <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Status Transaksi:</span>
                    @php
                        $statusMap = [
                            'pending' => 'Menunggu Pembayaran',
                            'paid' => 'Lunas (Verified)',
                            'processing' => 'Diproses Gudang',
                            'shipped' => 'Dalam Pengiriman',
                            'completed' => 'Transaksi Selesai',
                            'cancelled' => 'Dibatalkan',
                        ];
                    @endphp
                    <span class="font-bold text-emerald-700 uppercase tracking-wider text-xs">
                        {{ $statusMap[$order->status] ?? $order->status }}
                    </span>
                </div>

                <div>
                    <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Layanan Ekspedisi:</span>
                    <strong class="text-gray-900">{{ $order->courier_code ?: 'JNE Regular Service' }}</strong>
                    @if($order->tracking_number)
                    <div class="font-mono text-gray-700 text-xs mt-0.5">Resi: <strong>{{ $order->tracking_number }}</strong></div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabel Rincian Pembelian Produk -->
        <div class="py-8">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b-2 border-gray-900 text-[10px] uppercase tracking-wider font-extrabold text-gray-900">
                        <th class="py-3 pr-4">No.</th>
                        <th class="py-3 px-4">Deskripsi Tas & Koleksi</th>
                        <th class="py-3 px-4">Varian Warna</th>
                        <th class="py-3 px-4 text-center">Qty</th>
                        <th class="py-3 px-4 text-right">Harga Satuan</th>
                        <th class="py-3 pl-4 text-right">Jumlah (IDR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($order->items as $index => $item)
                    <tr>
                        <td class="py-4 pr-4 font-mono text-gray-500">{{ $index + 1 }}</td>
                        <td class="py-4 px-4">
                            <div class="font-bold text-gray-950 text-sm">{{ $item->product_name }}</div>
                            <div class="text-[10px] text-gray-500 mt-0.5">Brand Luxury Authenticated • 100% Original</div>
                        </td>
                        <td class="py-4 px-4 font-medium text-gray-700">
                            {{ $item->variant_name }}
                        </td>
                        <td class="py-4 px-4 text-center font-bold font-mono text-gray-900">
                            {{ $item->quantity }}
                        </td>
                        <td class="py-4 px-4 text-right font-mono text-gray-700">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td class="py-4 pl-4 text-right font-mono font-bold text-gray-950">
                            Rp {{ number_format($item->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Calculation Subtotal, Ongkir & Grand Total -->
        <div class="border-t-2 border-gray-900 pt-6 flex justify-end">
            <div class="w-full sm:w-72 space-y-2.5 text-xs">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal Produk:</span>
                    <span class="font-mono font-semibold text-gray-900">
                        Rp {{ number_format($order->grand_total - $order->shipping_cost, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Ongkos Kirim ({{ $order->courier_code ?: 'Kurir' }}):</span>
                    <span class="font-mono font-semibold text-gray-900">
                        {{ $order->shipping_cost > 0 ? 'Rp ' . number_format($order->shipping_cost, 0, ',', '.') : 'Rp 0' }}
                    </span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Asuransi Paket:</span>
                    <span class="font-mono font-semibold text-emerald-700">Sudah Termasuk</span>
                </div>
                <div class="border-t border-gray-300 pt-3 flex justify-between items-baseline">
                    <span class="font-extrabold uppercase tracking-wider text-gray-900 text-sm">Total Akhir:</span>
                    <span class="font-serif font-black text-xl text-rose-600 font-mono">
                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Catatan & Tanda Tangan Resmi -->
        <div class="mt-12 pt-8 border-t border-gray-200 grid grid-cols-1 sm:grid-cols-2 gap-8 text-xs text-gray-500 page-break-inside-avoid">
            <div>
                <h4 class="font-bold text-gray-900 uppercase text-[10px] tracking-wider mb-1.5">Syarat & Kebijakan Toko:</h4>
                <p class="leading-relaxed text-[11px]">
                    1. Seluruh tas mewah Mikael On Shop dijamin 100% keasliannya.<br>
                    2. Klaim garansi atau penukaran wajib menyertakan video unboxing utuh tanpa jeda.<br>
                    3. Terima kasih telah berbelanja koleksi luxury di Mikael On Shop.
                </p>
            </div>
            <div class="sm:text-right space-y-8">
                <div>
                    <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Otorisasi Manajemen</span>
                    <div class="font-serif font-bold text-gray-900 mt-1">Mikael On Shop Official Store</div>
                </div>
                <div class="text-[10px] text-gray-400 font-mono">
                    Authorized Electronic Invoice System<br>
                    ID: {{ md5($order->order_number . $order->created_at) }}
                </div>
            </div>
        </div>

    </div>

</body>
</html>
