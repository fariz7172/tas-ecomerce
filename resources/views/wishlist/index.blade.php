<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wishlist Saya — Mikael On Shop</title>
    
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 sm:py-0 sm:h-20 flex flex-wrap items-center justify-between gap-3">
            <a href="/" class="flex items-center gap-3 sm:gap-3.5 flex-shrink-0 group">
                <img src="{{ asset('assets/logo.png') }}" alt="Mikael on Shop" class="w-12 h-12 sm:w-14 sm:h-14 object-contain group-hover:scale-105 transition-transform drop-shadow-md">
                <div>
                    <span class="font-serif font-black text-lg sm:text-2xl tracking-[0.12em] sm:tracking-[0.16em] uppercase text-rosepet-dark block leading-none">Mikael <span class="font-normal text-rosepet-fresh italic">on Shop</span></span>
                    <span class="text-[8px] sm:text-[9px] uppercase tracking-widest text-rosepet-muted font-bold block mt-0.5 sm:mt-1">Official Member Area</span>
                </div>
            </a>
            
            <div class="flex items-center gap-2.5 sm:gap-4 flex-wrap">
                <a href="/" class="text-xs font-bold text-rosepet-dark hover:text-rosepet-fresh transition-colors">← Katalog Tas</a>
                <a href="{{ route('orders.history') }}" class="text-xs font-bold text-rosepet-dark hover:text-rosepet-fresh transition-colors">🛍️ Pesanan</a>
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
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-12 flex-1 w-full space-y-6 sm:space-y-8">
        @if(session('success'))
        <div class="p-3.5 sm:p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 font-bold flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">✕</button>
        </div>
        @endif

        <div>
            <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest text-rosepet-fresh">Personal Collection</span>
            <h1 class="font-serif text-2xl sm:text-4xl font-bold text-rosepet-dark mt-1">Wishlist Tas Impian Saya</h1>
            <p class="text-xs text-rosepet-muted mt-1">Daftar tas favorit yang Anda simpan untuk dipesan kapan saja.</p>
        </div>

        @if($wishlists->isEmpty())
        <div class="text-center py-16 sm:py-20 bg-white rounded-3xl border-2 border-dashed border-rosepet-border space-y-4 px-4">
            <span class="text-4xl sm:text-5xl block">💖</span>
            <h3 class="font-serif text-lg sm:text-xl font-bold text-rosepet-dark">Wishlist Anda Masih Kosong</h3>
            <p class="text-xs text-rosepet-muted max-w-sm mx-auto">Jelajahi koleksi tas kulit mewah kami dan klik ikon hati untuk menyimpannya di sini.</p>
            <a href="/#katalog" class="inline-block mt-2 px-6 py-3 rounded-2xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-xs uppercase tracking-wider shadow-md hover:shadow-lg transition-all">
                Mulai Eksplorasi Tas
            </a>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($wishlists as $item)
            @php
                $product = $item->product;
                $mainVariant = $product->variants->first();
                $displayImage = $product->images->first()?->image_path ? asset($product->images->first()->image_path) : ($mainVariant?->image_path ?? 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80');
                $price = $mainVariant?->price ?? 0;
                $promoPrice = $mainVariant?->promo_price ?: $price;

                $waText = "Halo Admin Mikael on Shop! Saya tertarik dan ingin bertanya detail tentang tas ini:\n\n"
                    . "👜 Nama Tas: {$product->name}\n"
                    . "🏷️ Kategori: {$product->category->name}\n"
                    . "💰 Harga: Rp " . number_format($promoPrice, 0, ',', '.') . "\n"
                    . "🔗 Link Produk: " . url("/product/{$product->slug}") . "\n\n"
                    . "Apakah stok varian tas ini masih tersedia?";
                $waUrl = "https://wa.me/6281288992211?text=" . urlencode($waText);
            @endphp
            <div class="bg-white rounded-3xl p-4 sm:p-5 border-2 border-rosepet-border shadow-sm flex flex-col justify-between space-y-4">
                <div class="relative rounded-2xl overflow-hidden bg-[#FAF7F8] h-48 sm:h-52 flex items-center justify-center p-3">
                    <img src="{{ $displayImage }}" alt="{{ $product->name }}" class="max-h-full max-w-full object-contain">
                    
                    <!-- Tombol Hapus Wishlist -->
                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3">
                        @csrf
                        <button type="submit" title="Hapus dari Wishlist" class="w-8 h-8 rounded-full bg-white/95 text-rose-600 hover:bg-rose-50 flex items-center justify-center shadow-md hover:scale-110 transition-all border border-rose-200">
                            <span class="text-base font-bold leading-none">✕</span>
                        </button>
                    </form>
                </div>

                <div class="space-y-1">
                    <span class="text-[10px] font-bold text-rosepet-muted uppercase">{{ $product->category->name }}</span>
                    <h3 class="font-serif font-bold text-base text-rosepet-dark">{{ $product->name }}</h3>
                    <div class="font-serif font-black text-lg text-rosepet-fresh">Rp {{ number_format($promoPrice, 0, ',', '.') }}</div>
                </div>

                <div class="pt-3 border-t border-rosepet-soft space-y-2">
                    <div class="flex gap-2">
                        <a href="{{ route('product.detail', $product->slug) }}" class="flex-1 py-2.5 px-3 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-xs uppercase tracking-wider text-center shadow-sm hover:shadow transition-all">
                            Pesan Sekarang
                        </a>
                        <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="py-2.5 px-3 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 font-bold text-xs transition-all border border-rose-200" title="Hapus Wishlist">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>

                    <!-- Tombol Tanya WhatsApp Admin -->
                    <a href="{{ $waUrl }}" target="_blank" class="w-full py-2 px-3 rounded-xl bg-[#25D366]/10 text-[#128C7E] hover:bg-[#25D366] hover:text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-all border border-[#25D366]/30">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>Tanya Admin via WhatsApp</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-rosepet-border/60 py-6 text-center text-xs text-rosepet-muted">
        <p>© 2026 Mikael On Shop. All Rights Reserved.</p>
    </footer>

</body>
</html>
