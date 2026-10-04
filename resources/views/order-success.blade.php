<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Diterima #{{ $order->order_number }} - Mikael on Shop</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,900;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        rosepet: {
                            light: '#FFF9FA',
                            soft: '#FCE7EC',
                            border: '#EBB4C4',
                            fresh: '#E87A90',
                            vibrant: '#E8617D',
                            deep: '#B76E79',
                            dark: '#3D2329',
                        }
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'Georgia', 'serif'],
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#FFF5F7] text-rosepet-dark font-sans selection:bg-rosepet-soft selection:text-rosepet-vibrant antialiased min-h-screen py-10 px-4 flex items-center justify-center">

    <div class="max-w-2xl w-full bg-white rounded-[2.5rem] border-2 border-rosepet-border shadow-xl p-8 sm:p-12 space-y-8">
        
        <!-- Header Status Dinamis Sesuai Status Bayar -->
        <div class="text-center space-y-3">
            @if($order->status === 'paid')
            <div class="w-16 h-16 rounded-full bg-emerald-50 border-2 border-emerald-300 text-emerald-600 flex items-center justify-center mx-auto text-3xl shadow-sm">
                ✓
            </div>
            <span class="text-[11px] font-black uppercase tracking-widest text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-full border border-emerald-300">
                Pembayaran Sukses Dibayarkan
            </span>
            <h1 class="font-serif text-3xl font-black text-rosepet-dark">Pembayaran Sukses Dibayarkan</h1>
            <p class="text-xs text-rosepet-muted max-w-md mx-auto">
                Dana Anda telah kami terima dengan aman. Pesanan segera disiapkan oleh gudang Mikael On Shop!
            </p>
            @elseif($order->payment_method === 'COD')
            <div class="w-16 h-16 rounded-full bg-amber-50 border-2 border-amber-300 text-amber-600 flex items-center justify-center mx-auto text-3xl shadow-sm">
                🚚
            </div>
            <span class="text-[11px] font-black uppercase tracking-widest text-amber-700 bg-amber-50 px-3.5 py-1.5 rounded-full border border-amber-300">
                Pesanan COD Siap Dikirim
            </span>
            <h1 class="font-serif text-3xl font-black text-rosepet-dark">Pesanan Anda Telah Diproses!</h1>
            <p class="text-xs text-rosepet-muted max-w-md mx-auto">
                Bayar tunai di tempat saat kurir mengantarkan tas ke alamat Anda.
            </p>
            @else
            <div class="w-16 h-16 rounded-full bg-rose-50 border-2 border-rose-300 text-rosepet-fresh flex items-center justify-center mx-auto text-3xl shadow-sm animate-pulse">
                ⏳
            </div>
            <span class="text-[11px] font-black uppercase tracking-widest text-rose-700 bg-rose-50 px-3.5 py-1.5 rounded-full border border-rose-300">
                Menunggu Pembayaran
            </span>
            <h1 class="font-serif text-3xl font-black text-rosepet-dark">Segera Lakukan Pembayaran Untuk Produk ini</h1>
            <p class="text-xs text-rosepet-muted max-w-md mx-auto">
                Selesaikan pembayaran Anda agar pesanan tas ini dapat segera dipacking dan dikirimkan oleh gudang kami.
            </p>
            @endif

            <p class="text-xs text-rosepet-muted">
                No. Order: <strong class="text-rosepet-dark font-mono bg-rosepet-soft/50 px-2 py-0.5 rounded">{{ $order->order_number }}</strong>
            </p>
        </div>

        <!-- Order Summary Card -->
        <div class="p-6 rounded-2xl bg-[#FFF9FA] border border-rosepet-soft space-y-4 text-xs">
            <div class="flex items-center justify-between border-b border-rosepet-soft pb-3 font-bold">
                <span class="text-rosepet-dark">Rincian Produk</span>
                <span class="text-rosepet-muted">Jumlah</span>
            </div>

            @foreach($order->items as $item)
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h4 class="font-bold text-rosepet-dark">{{ $item->product_name }}</h4>
                    <span class="text-[11px] text-rosepet-muted">Warna: <strong>{{ $item->variant_name }}</strong></span>
                </div>
                <div class="text-right">
                    <span class="text-rosepet-dark font-bold">{{ $item->quantity }} pcs</span>
                    <span class="block text-[11px] text-rosepet-fresh font-bold">Rp {{ number_format($item->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach

            <div class="pt-3 border-t border-rosepet-soft space-y-1.5 text-[11px]">
                <div class="flex justify-between text-rosepet-muted">
                    <span>Subtotal Produk:</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-rosepet-muted">
                    <span>Ongkos Kirim ({{ $order->courier_code }} - {{ $order->courier_service }}):</span>
                    <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm font-black text-rosepet-dark pt-2 border-t border-rosepet-soft">
                    <span>Total Tagihan:</span>
                    <span class="text-rosepet-fresh font-serif text-base">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Instruksi Pembayaran Sesuai Metode -->
        <div class="p-5 rounded-2xl border-2 border-rosepet-border space-y-3 text-xs">
            <div class="flex items-center justify-between">
                <span class="font-bold uppercase tracking-wider text-[10px] text-rosepet-muted">Metode Pembayaran</span>
                <span class="font-bold px-3 py-1 rounded-full text-[10px] {{ $order->payment_method === 'COD' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-rosepet-soft text-rosepet-deep' }}">
                    {{ $order->payment_channel ?: ($order->payment_method === 'COD' ? '💵 COD (Bayar di Tempat)' : ($order->payment_method === 'QRIS' ? '📱 QRIS Instan (Gopay/OVO/ShopeePay)' : '🏦 Virtual Account / Transfer Bank')) }}
                </span>
            </div>

            @if($order->payment_channel)
            <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center justify-between">
                <span class="text-[11px] text-emerald-800 font-bold">Saluran Transaksi:</span>
                <span class="text-xs font-black text-emerald-700 bg-white px-2.5 py-0.5 rounded-lg border border-emerald-300">
                    {{ $order->payment_channel }}
                </span>
            </div>
            @endif

            @if($order->payment_method === 'COD')
            <p class="text-xs text-rosepet-dark leading-relaxed">
                Pesanan Anda telah <strong>langsung masuk ke antrean gudang</strong> untuk dipacking. Harap siapkan uang tunai sebesar <strong>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong> saat kurir mengantarkan paket tas ke alamat Anda.
            </p>
            @elseif($order->status === 'paid')
            <div class="p-3.5 bg-emerald-50 rounded-xl border border-emerald-200 space-y-1">
                <span class="text-[10px] uppercase font-bold text-emerald-800 block">Status Pembayaran: LUNAS</span>
                <p class="text-[11px] text-emerald-900 font-medium">
                    Pembayaran melalui <strong>{{ $order->payment_channel ?: 'Midtrans Gateway' }}</strong> telah berhasil diverifikasi otomatis oleh sistem. Pesanan Anda segera dipacking!
                </p>
            </div>
            @else
            <div class="p-4 bg-[#FFF9FA] rounded-2xl border-2 border-rosepet-soft space-y-3">
                <div class="flex items-center justify-between border-b border-rosepet-soft/80 pb-2">
                    <span class="text-[10px] uppercase font-bold text-rosepet-muted">Instruksi Pembayaran Digital</span>
                    <span class="text-[10px] font-bold text-rosepet-fresh bg-white px-2 py-0.5 rounded-full border border-rosepet-soft">
                        Batas Waktu: 15 Menit
                    </span>
                </div>

                @php
                    $payload = json_decode($order->payment_payload, true);
                    $paymentType = $payload['payment_type'] ?? '';
                    $va = $payload['va_numbers'][0]['va_number'] ?? ($payload['permata_va_number'] ?? null);
                    $bank = strtoupper($payload['va_numbers'][0]['bank'] ?? 'Bank');
                    $isQris = ($order->payment_method === 'QRIS') || ($paymentType === 'qris') || str_contains(strtolower($order->payment_channel ?? ''), 'qris');
                @endphp

                <!-- 1. Tampilan Barcode QRIS Resmi -->
                @if($isQris)
                <div class="text-center py-2 space-y-3 bg-white rounded-2xl p-4 border border-rosepet-border/60 shadow-sm">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rosepet-soft/50 text-[10px] font-black text-rosepet-dark uppercase tracking-wider">
                        <span>📱</span>
                        <span>Scan Barcode QRIS Resmi</span>
                    </div>

                    <!-- Kotak Gambar Barcode QRIS -->
                    <div class="w-56 h-56 mx-auto bg-white p-2.5 rounded-2xl border-2 border-rosepet-dark/10 shadow-md flex items-center justify-center relative group">
                        <img src="{{ route('api.order.qris_image', $order->order_number) }}" alt="QRIS Barcode" class="w-full h-full object-contain">
                    </div>

                    <div class="space-y-1">
                        <div class="text-xs font-bold text-rosepet-dark">
                            Dapat di-scan dengan: <span class="text-rosepet-fresh">GoPay, OVO, Dana, BCA Mobile, ShopeePay, LinkAja</span> & Seluruh m-Banking Indonesia
                        </div>
                        <p class="text-[10px] text-rosepet-muted">
                            Total Bayar Tepat: <strong class="text-rosepet-dark font-mono text-xs">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong>
                        </p>
                    </div>

                    <div class="pt-2 border-t border-rosepet-soft/60 flex justify-center gap-2">
                        <a href="{{ route('api.order.qris_image', $order->order_number) }}" download="QRIS-{{ $order->order_number }}.png" class="py-1.5 px-3 rounded-xl bg-rosepet-soft hover:bg-rosepet-border text-rosepet-deep font-bold text-[10px] flex items-center gap-1 transition-all">
                            <span>📥 Unduh Barcode QR</span>
                        </a>
                        <button onclick="window.location.reload()" class="py-1.5 px-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-[10px] border border-emerald-200 flex items-center gap-1 transition-all">
                            <span>🔄 Cek Status Bayar</span>
                        </button>
                    </div>
                </div>
                @endif

                <!-- 2. Tampilan Nomor Virtual Account (Jika Transfer VA) -->
                @if($va)
                <div class="p-3 bg-white rounded-xl border border-rosepet-soft flex items-center justify-between">
                    <div>
                        <span class="text-[9px] text-rosepet-muted block uppercase font-bold">Nomor Virtual Account {{ $bank }}:</span>
                        <span class="font-mono font-black text-base text-rosepet-fresh tracking-wider select-all">{{ $va }}</span>
                    </div>
                    <button onclick="navigator.clipboard.writeText('{{ $va }}'); alert('Nomor Virtual Account disalin!');" class="text-[10px] text-rosepet-deep bg-rosepet-soft/60 hover:bg-rosepet-soft px-2.5 py-1 rounded-lg font-bold transition-colors">
                        Salin No. VA
                    </button>
                </div>
                @endif

                <p class="text-[10px] text-rosepet-muted text-center pt-1">
                    Halaman ini otomatis mendeteksi ketika pembayaran telah selesai dilakukan.
                </p>
            </div>
            @endif
        </div>

        <!-- Data Alamat Penerima -->
        <div class="p-4 rounded-xl bg-gray-50 text-[11px] text-rosepet-muted space-y-1">
            <span class="font-bold text-rosepet-dark uppercase text-[10px] block">Tujuan Pengiriman:</span>
            <p><strong class="text-rosepet-dark">{{ $order->customer_name }}</strong> ({{ $order->customer_phone }})</p>
            <p>{{ $order->address_line }}, {{ $order->district }}, {{ $order->city }}, {{ $order->province }}</p>
        </div>

        <!-- Tombol Aksi -->
        @php
            $waMsg = urlencode("Halo Mikael on Shop! Saya baru saja membuat pesanan dengan nomor order #{$order->order_number} ({$order->items->first()?->product_name} - {$order->items->first()?->variant_name}) senilai Rp " . number_format($order->grand_total, 0, ',', '.') . " melalui metode {$order->payment_method}. Mohon diproses ya!");
            $waUrl = "https://wa.me/6281288992211?text={$waMsg}";
        @endphp
        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <a href="{{ $waUrl }}" target="_blank" class="flex-1 py-3.5 px-6 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold text-xs uppercase tracking-wider text-center shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                <span>💬 Konfirmasi via WhatsApp</span>
            </a>
            <a href="{{ route('home') }}" class="py-3.5 px-6 rounded-2xl bg-rosepet-soft text-rosepet-deep font-bold text-xs uppercase tracking-wider text-center hover:bg-rosepet-border transition-all">
                Kembali Belanja
            </a>
        </div>

    </div>

</body>
</html>
