<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Pesanan Saya — Mikael On Shop</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        rosepet: {
                            light: '#FFF9FA',
                            glow: '#FDF0F3',
                            soft: '#FCE7EC',
                            border: '#EBB4C4',
                            fresh: '#E87A90',
                            vibrant: '#D85A75',
                            deep: '#B76E79',
                            dark: '#4A2E35',
                            muted: '#8C6D75',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-rosepet-light text-rosepet-dark font-sans antialiased min-h-screen flex flex-col justify-between">

    <!-- Header Navbar -->
    <header class="bg-white/95 backdrop-blur-md border-b border-rosepet-border sticky top-0 z-40">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-3 sm:py-0 sm:h-20 flex flex-wrap items-center justify-between gap-3">
            <a href="/" class="flex items-center gap-3 sm:gap-3.5 flex-shrink-0 group">
                <img src="{{ asset('assets/logo.png') }}" alt="Mikael on Shop" class="w-12 h-12 sm:w-14 sm:h-14 object-contain group-hover:scale-105 transition-transform drop-shadow-md">
                <div>
                    <span class="font-serif font-black text-lg sm:text-2xl tracking-[0.12em] sm:tracking-[0.16em] uppercase text-rosepet-dark block leading-none">Mikael <span class="font-normal text-rosepet-fresh italic">on Shop</span></span>
                    <span class="text-[8px] sm:text-[9px] uppercase tracking-widest text-rosepet-muted font-bold block mt-0.5 sm:mt-1">Member Dashboard</span>
                </div>
            </a>
            
            <div class="flex items-center gap-2.5 sm:gap-4 flex-wrap">
                <a href="/" class="text-xs font-bold text-rosepet-dark hover:text-rosepet-fresh transition-colors">← Beranda</a>
                <a href="{{ route('wishlist.index') }}" class="text-xs font-bold text-rosepet-dark hover:text-rosepet-fresh transition-colors">♥ Wishlist</a>
                <span class="text-xs text-rosepet-muted hidden sm:inline">Halo, <strong>{{ Auth::user()->name }}</strong></span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-2.5 py-1.5 sm:px-3 sm:py-1.5 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-bold transition-all border border-rose-200">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-6 sm:py-12 flex-1 w-full space-y-6 sm:space-y-8">
        <div>
            <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest text-rosepet-fresh">Order Dashboard</span>
            <h1 class="font-serif text-2xl sm:text-4xl font-bold text-rosepet-dark mt-1">Riwayat Pesanan Saya</h1>
            <p class="text-xs text-rosepet-muted mt-1">Pantau status pembayaran, proses packing gudang, dan nomor resi pengiriman tas Anda.</p>
        </div>

        @if($orders->isEmpty())
        <div class="text-center py-16 sm:py-20 bg-white rounded-3xl border-2 border-dashed border-rosepet-border space-y-4 px-4">
            <span class="text-4xl sm:text-5xl block">🛍️</span>
            <h3 class="font-serif text-lg sm:text-xl font-bold text-rosepet-dark">Belum Ada Riwayat Pesanan</h3>
            <p class="text-xs text-rosepet-muted max-w-sm mx-auto">Anda belum pernah melakukan pemesanan tas. Jelajahi katalog dan miliki tas impian Anda sekarang.</p>
            <a href="/#katalog" class="inline-block mt-2 px-6 py-3 rounded-2xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-xs uppercase tracking-wider shadow-md hover:shadow-lg transition-all">
                Mulai Berbelanja
            </a>
        </div>
        @else
        <div class="space-y-4 sm:space-y-6">
            @foreach($orders as $order)
            @php
                $statusLabels = [
                    'pending' => ['text' => '⏳ Menunggu Bayar', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                    'paid' => ['text' => '💰 Sudah Lunas', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                    'processing' => ['text' => '📦 Dipacking', 'class' => 'bg-indigo-50 text-indigo-700 border-indigo-200'],
                    'shipped' => ['text' => '🚚 Dikirim', 'class' => 'bg-sky-50 text-sky-700 border-sky-200'],
                    'completed' => ['text' => '✅ Selesai', 'class' => 'bg-gray-50 text-gray-700 border-gray-200'],
                    'cancelled' => ['text' => '❌ Dibatalkan', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
                ];
                $badge = $statusLabels[$order->status] ?? ['text' => $order->status, 'class' => 'bg-gray-50 text-gray-700 border-gray-200'];
            @endphp
            <div class="bg-white rounded-3xl p-4 sm:p-6 border-2 border-rosepet-border shadow-sm space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-rosepet-soft pb-3">
                    <div>
                        <span class="text-[9px] sm:text-[10px] font-bold text-rosepet-muted uppercase">Nomor Pesanan</span>
                        <h3 class="font-mono font-bold text-xs sm:text-sm text-rosepet-dark">{{ $order->order_number }}</h3>
                        <span class="text-[9px] sm:text-[10px] text-rosepet-muted">{{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                    </div>
                    <div class="text-right flex flex-col items-end gap-1">
                        <span class="px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full text-[11px] sm:text-xs font-bold border {{ $badge['class'] }}">
                            {{ $badge['text'] }}
                        </span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-rosepet-muted uppercase">Metode: {{ $order->payment_method }}</span>
                    </div>
                </div>

                <!-- Daftar Item Pesanan -->
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex items-center justify-between text-xs gap-2">
                        <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                            <span class="w-8 h-8 rounded-xl bg-[#FAF7F8] flex items-center justify-center font-bold text-rosepet-fresh border border-rosepet-soft flex-shrink-0">👜</span>
                            <div class="truncate">
                                <h4 class="font-bold text-rosepet-dark truncate">{{ $item->product_name }}</h4>
                                <span class="text-[10px] text-rosepet-muted block">Varian: {{ $item->variant_name }} • {{ $item->quantity }} pcs</span>
                            </div>
                        </div>
                        <div class="font-bold text-rosepet-dark flex-shrink-0">
                            Rp {{ number_format($item->total_price, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Informasi Resi & Total Tagihan -->
                <div class="pt-3 border-t border-rosepet-soft flex flex-wrap items-center justify-between gap-3 sm:gap-4">
                    <div class="w-full sm:w-auto">
                        @if($order->tracking_number)
                        <div class="inline-flex items-center gap-1.5 sm:gap-2 px-3 py-1.5 rounded-xl bg-sky-50 border border-sky-200 text-sky-800 text-[11px] sm:text-xs font-mono font-bold">
                            <span>🚚 Resi: <strong>{{ $order->tracking_number }}</strong></span>
                            <span class="text-[9px] sm:text-[10px] text-sky-600 font-sans">({{ $order->courier_code }})</span>
                        </div>
                        @else
                        <span class="text-[11px] sm:text-xs text-rosepet-muted italic">Resi pengiriman muncul setelah dikirim gudang.</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-3 sm:gap-4 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-rosepet-soft/60">
                        <div class="text-left sm:text-right">
                            <span class="text-[9px] sm:text-[10px] font-bold uppercase text-rosepet-muted block">Total Tagihan:</span>
                            <span class="font-serif font-black text-lg sm:text-xl text-rosepet-fresh">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('order.success', $order->order_number) }}" class="py-2 px-3.5 sm:px-4 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white text-[11px] sm:text-xs font-bold uppercase tracking-wider shadow-sm hover:shadow transition-all whitespace-nowrap">
                            Lihat Invoice
                        </a>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="pt-4">
                {{ $orders->links() }}
            </div>
        </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-rosepet-border/60 py-6 text-center text-xs text-rosepet-muted">
        <p>© 2026 Mikael On Shop. All Rights Reserved.</p>
    </footer>

</body>
</html>
