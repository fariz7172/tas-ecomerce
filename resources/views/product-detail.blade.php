<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Mikael On Shop Luxury Atelier</title>
    
    <meta name="description" content="{{ Str::limit(strip_tags($product->description), 150) }}">
    <link rel="canonical" href="{{ route('product.detail', $product->slug) }}">

    <!-- Open Graph Meta Tags (Untuk Preview Kartu Facebook, WhatsApp & Twitter) -->
    @php
        $ogImg = $product->images->first()?->image_path ? asset($product->images->first()->image_path) : asset('assets/logo.png');
        $minPrice = $product->variants->min('promo_price') ?: $product->variants->min('price');
    @endphp
    <meta property="og:type" content="product">
    <meta property="og:title" content="{{ $product->name }} - Mikael On Shop">
    <meta property="og:description" content="{{ $product->material }} • {{ $product->dimensions_cm }} • Mulai Rp {{ number_format($minPrice ?? 0, 0, ',', '.') }}. 100% Original Leather & COD Se-Indonesia.">
    <meta property="og:image" content="{{ $ogImg }}">
    <meta property="og:url" content="{{ route('product.detail', $product->slug) }}">
    <meta property="og:site_name" content="Mikael On Shop">

    <!-- Google Fonts & Tailwind CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,900;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        rosepet: {
                            light: '#FFF9FA',
                            canvas: '#FDF0F3',
                            soft: '#FCE7EC',
                            border: '#EBB4C4',
                            fresh: '#E87A90',
                            vibrant: '#E8617D',
                            deep: '#B76E79',
                            dark: '#3D2329',
                            gold: '#D4AF37'
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

    <!-- Midtrans Snap JS & Three.js CDN -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY', 'Mid-client-rkt_8RrQGvYiucnK') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/DRACOLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
</head>
<body class="bg-rosepet-light text-rosepet-dark font-sans selection:bg-rosepet-soft selection:text-rosepet-vibrant antialiased">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-rosepet-deep via-rosepet-fresh to-rosepet-deep text-white text-xs font-semibold py-2.5 px-4 text-center tracking-wider uppercase border-b border-rosepet-border/40">
        ✨ Koleksi Tas Mewah Terverifikasi • Pengiriman Prioritas Asuransi Penuh
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-rosepet-border/50 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 sm:py-0 sm:h-20 flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-3 sm:gap-3.5 flex-shrink-0 group">
                <img src="{{ asset('assets/logo.png') }}" alt="Mikael on Shop" class="w-12 h-12 sm:w-14 sm:h-14 object-contain group-hover:scale-105 transition-transform drop-shadow-md">
                <div>
                    <span class="font-serif text-lg sm:text-2xl font-black tracking-wider sm:tracking-widest text-rosepet-dark block leading-none">Mikael On Shop</span>
                    <span class="text-[8px] sm:text-[9px] tracking-[0.2em] sm:tracking-[0.3em] uppercase text-rosepet-deep font-bold">Luxury Leather Atelier</span>
                </div>
            </a>

            <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                <a href="{{ route('home') }}#katalog" class="text-xs font-bold uppercase tracking-wider text-rosepet-deep hover:text-rosepet-vibrant transition-colors hidden md:inline">
                    ← Katalog
                </a>
                <a href="{{ route('wishlist.index') }}" class="px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-full border border-rosepet-border bg-white text-rose-600 text-[11px] sm:text-xs font-bold hover:bg-rose-50 transition-all flex items-center gap-1 sm:gap-1.5 shadow-sm">
                    <span>♥</span>
                    <span class="hidden xs:inline sm:inline">Wishlist</span>
                </a>
                @auth
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <a href="{{ route('orders.history') }}" class="px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-full border border-rosepet-border bg-white text-rosepet-dark text-[11px] sm:text-xs font-bold hover:text-rosepet-fresh transition-all flex items-center gap-1 sm:gap-1.5 shadow-sm">
                        <span>🛍️</span>
                        <span>Pesanan</span>
                    </a>
                    <span class="text-xs text-rosepet-muted hidden lg:inline">Halo, <strong>{{ Auth::user()->name }}</strong></span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-2.5 py-1.5 sm:px-3 sm:py-1.5 rounded-full bg-rose-50 text-rose-700 hover:bg-rose-100 text-[11px] sm:text-xs font-bold border border-rose-200 transition-all">
                            Keluar
                        </button>
                    </form>
                </div>
                @else
                <a href="{{ route('login') }}" class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-white border border-rosepet-border text-rosepet-dark hover:text-rosepet-fresh text-[11px] sm:text-xs font-bold transition-all shadow-sm">
                    Masuk
                </a>
                @endauth
                <a href="https://wa.me/6281288992211?text=Halo%20Mikael%20On%20Shop,%20saya%20tertarik%20dengan%20tas%20{{ urlencode($product->name) }}" target="_blank" class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-[11px] sm:text-xs uppercase tracking-wider shadow-md hover:shadow-rosepet-fresh/30 transition-all flex items-center gap-1">
                    <span>WA</span>
                </a>
            </div>
        </div>
    </header>

    @php
        $mainVariant = $product->variants->first();
        $price = $mainVariant?->price ?? 0;
        $promoPrice = $mainVariant?->promo_price ?? $price;
        $firstImg = $product->images->first()?->image_path ? asset($product->images->first()->image_path) : ($mainVariant?->image_path ?? 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80');
    @endphp

    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-6 pt-6 pb-2 text-xs text-rosepet-deep/80 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
        <span>/</span>
        <a href="{{ route('home') }}#katalog" class="hover:underline">Katalog Tas</a>
        <span>/</span>
        <span>{{ $product->category->name }}</span>
        <span>/</span>
        <span class="text-rosepet-dark font-bold">{{ $product->name }}</span>
    </div>

    <!-- Main Product Detail Viewport -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-4 sm:py-6 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10 items-start">

            <!-- Sisi Kiri: Media Showcase (Foto & 3D Interactive) (Col 6) -->
            <div class="lg:col-span-6 space-y-4">
                
                <!-- Main Visual Frame (Click to open Lightbox) -->
                <div class="bg-white rounded-3xl p-3 sm:p-4 border-2 border-rosepet-border shadow-sm">
                    
                    <!-- Media Type Switcher -->
                    <div class="flex items-center justify-between mb-3 border-b border-rosepet-soft pb-2.5">
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="switchDetailMedia('photo')" id="btn-show-photo" class="px-3 sm:px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white text-[11px] sm:text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                                <span>📷 Foto Produk</span>
                            </button>
                            @if($product->model_3d_path)
                            <button type="button" onclick="switchDetailMedia('3d')" id="btn-show-3d" class="px-3 sm:px-3.5 py-1.5 rounded-xl bg-rosepet-soft text-rosepet-deep hover:bg-rosepet-fresh hover:text-white text-[11px] sm:text-xs font-bold flex items-center gap-1.5 transition-all">
                                <span>🎮 3D View (360°)</span>
                            </button>
                            @endif
                        </div>

                        <span class="text-[9px] sm:text-[10px] text-rosepet-deep font-bold bg-rosepet-soft/50 px-2 sm:px-2.5 py-1 rounded-full">
                            🔍 Klik perbesar
                        </span>
                    </div>

                    <!-- 1. Foto Utama Viewport -->
                    <div id="detail-photo-frame" onclick="openImageLightbox(document.getElementById('current-detail-img').src, '{{ addslashes($product->name) }}')" class="relative w-full h-[320px] sm:h-[420px] md:h-[460px] rounded-2xl overflow-hidden bg-[#FAF7F8] border border-rosepet-soft/60 flex items-center justify-center p-3 sm:p-4 cursor-zoom-in group">
                        <img id="current-detail-img" src="{{ $firstImg }}" alt="{{ $product->name }}" class="w-full h-full object-contain filter drop-shadow-sm group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute bottom-3 right-3 sm:bottom-4 sm:right-4 px-2.5 py-1 sm:px-3 sm:py-1.5 rounded-full bg-white/90 backdrop-blur-md border border-rosepet-soft text-[9px] sm:text-[10px] font-bold text-rosepet-dark flex items-center gap-1.5 shadow-sm opacity-80 group-hover:opacity-100 transition-opacity">
                            <svg class="w-3.5 h-3.5 text-rosepet-fresh" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3-0H7"/></svg>
                            <span>Perbesar Lightbox</span>
                        </div>
                    </div>

                    <!-- 2. Viewport 3D Canvas Interaktif -->
                    @if($product->model_3d_path)
                    <div id="detail-3d-frame" class="hidden relative w-full h-[320px] sm:h-[420px] md:h-[460px] rounded-2xl overflow-hidden bg-gradient-to-b from-[#FFF5F7] to-[#FDF0F3] border border-rosepet-soft/60">
                        <canvas id="detail-storefront-canvas" class="w-full h-full cursor-grab active:cursor-grabbing"></canvas>
                        
                        <div class="absolute top-3 left-3 sm:top-4 sm:left-4 px-2.5 py-1 sm:px-3 sm:py-1.5 rounded-full bg-white/85 backdrop-blur-md border border-rosepet-soft text-[9px] sm:text-[10px] font-bold text-rosepet-dark flex items-center gap-1.5 shadow-sm pointer-events-none">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Putar 360° & Zoom Bebas</span>
                        </div>

                        <button type="button" onclick="open3dLightbox('{{ asset($product->model_3d_path) }}', '{{ addslashes($product->name) }}')" class="absolute bottom-3 right-3 sm:bottom-4 sm:right-4 px-3 py-1.5 sm:px-3.5 sm:py-1.5 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white text-[10px] sm:text-[11px] font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-1.5 z-10">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                            <span>Layar Penuh 3D</span>
                        </button>

                        <div id="store-3d-loader" class="absolute inset-0 bg-[#FFF5F7]/80 backdrop-blur-sm flex flex-col items-center justify-center gap-2">
                            <svg class="animate-spin h-6 w-6 text-rosepet-fresh" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span class="text-xs font-bold text-rosepet-dark">Memuat Model 3D Tas...</span>
                        </div>
                    </div>
                    @endif

                    <!-- Strip Thumbnail Foto -->
                    @if($product->images->count() > 1)
                    <div class="grid grid-cols-5 gap-2.5 mt-3 pt-3 border-t border-rosepet-soft/60">
                        @foreach($product->images as $img)
                        <div onclick="selectDetailPhoto('{{ asset($img->image_path) }}')" class="cursor-pointer h-16 rounded-xl overflow-hidden bg-[#FAF7F8] border-2 border-rosepet-soft hover:border-rosepet-fresh transition-all p-1 flex items-center justify-center">
                            <img src="{{ asset($img->image_path) }}" alt="{{ $img->alt_text }}" class="w-full h-full object-contain">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Keunggulan Garansi Mikael On Shop -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 sm:gap-3">
                    <div class="p-3 sm:p-3.5 rounded-2xl bg-white border border-rosepet-soft text-center space-y-0.5 sm:space-y-1">
                        <span class="text-base sm:text-lg block">🛡️</span>
                        <h4 class="text-[10px] sm:text-[11px] font-bold text-rosepet-dark">100% Genuine Leather</h4>
                        <p class="text-[8px] sm:text-[9px] text-rosepet-muted">Teruji keaslian material</p>
                    </div>
                    <div class="p-3 sm:p-3.5 rounded-2xl bg-white border border-rosepet-soft text-center space-y-0.5 sm:space-y-1">
                        <span class="text-base sm:text-lg block">📦</span>
                        <h4 class="text-[10px] sm:text-[11px] font-bold text-rosepet-dark">COD & Asuransi</h4>
                        <p class="text-[8px] sm:text-[9px] text-rosepet-muted">Bisa bayar di tempat</p>
                    </div>
                    <div class="p-3 sm:p-3.5 rounded-2xl bg-white border border-rosepet-soft text-center space-y-0.5 sm:space-y-1">
                        <span class="text-base sm:text-lg block">🔄</span>
                        <h4 class="text-[10px] sm:text-[11px] font-bold text-rosepet-dark">Garansi 14 Hari</h4>
                        <p class="text-[8px] sm:text-[9px] text-rosepet-muted">Jaminan tukar produk</p>
                    </div>
                </div>

            </div>

            <!-- Sisi Kanan: Informasi Produk, Harga, Variasi, & Deskripsi (Col 6) -->
            <div class="lg:col-span-6 space-y-6">

                <!-- Header Info & Harga -->
                <div class="bg-white rounded-3xl p-6 border-2 border-rosepet-border shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full bg-rosepet-soft text-rosepet-deep text-[11px] font-bold">
                            {{ $product->brand?->name ?? 'Mikael On Shop' }} • {{ $product->category->name }}
                        </span>
                        @if($product->is_featured)
                        <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold">
                            ★ Pilihan Utama
                        </span>
                        @endif
                    </div>

                    <h1 class="font-serif text-3xl md:text-4xl font-black text-rosepet-dark leading-tight">
                        {{ $product->name }}
                    </h1>

                    <div class="p-4 rounded-2xl bg-[#FFF9FA] border border-rosepet-soft/70 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-rosepet-muted">Harga Spesial</span>
                            <div class="font-serif font-black text-3xl text-rosepet-fresh">
                                Rp {{ number_format($promoPrice, 0, ',', '.') }}
                            </div>
                            @if($price > $promoPrice)
                            <div class="text-xs text-rosepet-muted line-through mt-0.5">
                                Rp {{ number_format($price, 0, ',', '.') }}
                            </div>
                            @endif
                        </div>

                        <div class="text-right">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-rosepet-muted">Status Stok</span>
                            <div class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200 inline-block mt-0.5">
                                Siap Kirim ({{ $product->variants->sum('stock') }} pcs)
                            </div>
                        </div>
                    </div>

                    <!-- Pilihan Varian Warna & Aksi Pemesanan -->
                    <form action="{{ route('checkout.page') }}" method="GET" class="space-y-5 pt-2">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-rosepet-dark mb-2">
                                1. Pilih Varian Warna Tas:
                            </label>
                            <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-2.5">
                                @foreach($product->variants as $index => $variant)
                                @php $isOutOfStock = $variant->stock <= 0; @endphp
                                <label class="{{ $isOutOfStock ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }}">
                                    <input type="radio" name="variant_id" value="{{ $variant->id }}" data-stock="{{ $variant->stock }}" {{ $index === 0 ? 'checked' : '' }} onchange="updateSelectedVariantPrice({{ $variant->price }}, {{ $variant->promo_price ?: $variant->price }}, {{ $variant->stock }})" class="peer sr-only">
                                    <div class="p-3 rounded-2xl border-2 border-rosepet-soft bg-[#FFF9FA] peer-checked:border-rosepet-fresh peer-checked:bg-white peer-checked:shadow-sm transition-all flex items-center gap-2.5 relative">
                                        @if($isOutOfStock)
                                        <span class="absolute top-1.5 right-1.5 bg-rose-600 text-white text-[8px] font-black px-1.5 py-0.2 rounded-full uppercase tracking-tighter">Habis</span>
                                        @endif
                                        <span class="w-4 h-4 rounded-full border border-black/10 shadow-sm flex-shrink-0" style="background-color: {{ $variant->color_hex }}"></span>
                                        <div>
                                            <div class="font-bold text-xs text-rosepet-dark leading-tight">{{ $variant->color_name }}</div>
                                            <div class="text-[10px] {{ $isOutOfStock ? 'text-rose-600 font-bold' : 'text-rosepet-muted' }}">
                                                {{ $isOutOfStock ? 'Stok: 0' : 'Stok: ' . $variant->stock }}
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Jumlah Kuantitas & Info Layanan -->
                        <div class="p-4 rounded-2xl bg-[#FFF9FA] border-2 border-rosepet-border space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-dark">Jumlah Pembelian</label>
                                    <div id="stock-info-text" class="text-[10px] text-rosepet-muted">Tersedia {{ $product->variants->first()->stock ?? 0 }} pcs</div>
                                </div>
                                <div class="w-32">
                                    <input type="number" name="quantity" id="checkout-qty" min="1" max="{{ $product->variants->first()->stock ?? 10 }}" value="1" oninput="validateQuantityInput()" onchange="validateQuantityInput()" class="w-full bg-white rounded-xl px-3.5 py-2 text-center text-sm text-rosepet-dark border-2 border-rosepet-soft outline-none focus:border-rosepet-fresh font-bold">
                                </div>
                            </div>

                            <div class="p-3 bg-white rounded-xl border border-rosepet-soft flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2 text-emerald-700 font-semibold">
                                    <span>🚚</span>
                                    <span>Tersedia COD (Bebas Ongkir) & Ekspedisi Reguler</span>
                                </div>
                                <span class="text-[10px] font-bold text-rosepet-fresh uppercase">Pilih di Checkout</span>
                            </div>

                            <!-- CTA Tombol Pesan & Wishlist -->
                            @auth
                                @if(Auth::user()->role === 'admin')
                                <div class="p-3.5 rounded-2xl bg-amber-50 border-2 border-amber-200 text-center space-y-1">
                                    <div class="text-xs font-bold text-amber-800 flex items-center justify-center gap-1.5">
                                        <span>🛡️ Mode Administrator Aktif</span>
                                    </div>
                                    <p class="text-[11px] text-amber-700">
                                        Anda sedang login sebagai Admin. Akun admin tidak dapat memesan barang toko sendiri.
                                    </p>
                                    <a href="{{ route('admin.dashboard') }}" class="inline-block mt-1 text-[11px] font-bold text-rosepet-fresh hover:underline">
                                        Buka Admin Panel Dashboard →
                                    </a>
                                </div>
                                @else
                                <div class="flex gap-2.5 pt-1">
                                    <button type="submit" id="btn-submit-order" class="flex-1 py-3.5 px-6 rounded-2xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant hover:shadow-lg hover:shadow-rosepet-fresh/30 text-white font-bold text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span id="btn-submit-text">Lanjut ke Checkout Pesanan</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                    
                                    <a href="{{ route('wishlist.toggle', $product->id) }}" onclick="event.preventDefault(); document.getElementById('wishlist-form-detail').submit();" class="px-4 py-3.5 rounded-2xl border-2 border-rosepet-border bg-white hover:bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-base transition-colors shadow-sm" title="Simpan ke Wishlist">
                                        {{ $isWishlisted ? '♥' : '♡' }}
                                    </a>
                                </div>
                                @endif
                            @else
                            <div class="flex gap-2.5 pt-1">
                                <button type="submit" id="btn-submit-order" class="flex-1 py-3.5 px-6 rounded-2xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant hover:shadow-lg hover:shadow-rosepet-fresh/30 text-white font-bold text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span id="btn-submit-text">Lanjut ke Checkout Pesanan</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                                
                                <a href="{{ route('wishlist.toggle', $product->id) }}" onclick="event.preventDefault(); document.getElementById('wishlist-form-detail').submit();" class="px-4 py-3.5 rounded-2xl border-2 border-rosepet-border bg-white hover:bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-base transition-colors shadow-sm" title="Simpan ke Wishlist">
                                    {{ $isWishlisted ? '♥' : '♡' }}
                                </a>
                            </div>
                            @endauth
                        </div>
                    </form>

                    <form id="wishlist-form-detail" action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="hidden">
                        @csrf
                    </form>

                    <!-- Tombol Aksi Alternatif WhatsApp -->
                    <div class="pt-2 text-center">
                        <a href="https://wa.me/6281288992211?text=Halo%20Mikael%20on%20Shop,%20saya%20ingin%20tanya%20detail%20tas%20{{ urlencode($product->name) }}" target="_blank" class="text-xs text-rosepet-muted hover:text-rosepet-fresh font-semibold transition-colors inline-flex items-center gap-1">
                            <span>Ingin konsultasi via WhatsApp sebelum pesan? Klik di sini</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>

                <!-- Format Deskripsi Produk (Sesuai Permintaan Fariz) -->
                <div class="bg-white rounded-3xl p-6 border-2 border-rosepet-border shadow-sm space-y-3">
                    <h3 class="font-serif text-lg font-bold text-rosepet-dark border-b border-rosepet-soft pb-3 flex items-center gap-2">
                        <span>Deskripsi & Karakteristik Tas</span>
                    </h3>
                    <div class="prose prose-sm max-w-none text-rosepet-dark text-xs leading-relaxed space-y-2">
                        {!! $product->description !!}
                    </div>
                </div>

                <!-- Spesifikasi Detail Fisik Tas -->
                <div class="bg-white rounded-3xl p-6 border-2 border-rosepet-border shadow-sm space-y-4">
                    <h3 class="font-serif text-lg font-bold text-rosepet-dark border-b border-rosepet-soft pb-3">
                        Spesifikasi Teknis
                    </h3>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="p-3 rounded-xl bg-[#FFF9FA] border border-rosepet-soft">
                            <span class="text-[10px] font-bold uppercase text-rosepet-muted block">Material</span>
                            <strong class="text-rosepet-dark block mt-0.5">{{ $product->material }}</strong>
                        </div>
                        <div class="p-3 rounded-xl bg-[#FFF9FA] border border-rosepet-soft">
                            <span class="text-[10px] font-bold uppercase text-rosepet-muted block">Dimensi</span>
                            <strong class="text-rosepet-dark block mt-0.5">{{ $product->dimensions_cm }}</strong>
                        </div>
                        <div class="p-3 rounded-xl bg-[#FFF9FA] border border-rosepet-soft">
                            <span class="text-[10px] font-bold uppercase text-rosepet-muted block">Berat Tas</span>
                            <strong class="text-rosepet-dark block mt-0.5">{{ $product->weight_grams }} gram</strong>
                        </div>
                        <div class="p-3 rounded-xl bg-[#FFF9FA] border border-rosepet-soft">
                            <span class="text-[10px] font-bold uppercase text-rosepet-muted block">Tipe Penutup</span>
                            <strong class="text-rosepet-dark block mt-0.5">{{ $product->closure_type }}</strong>
                        </div>
                        <div class="p-3 rounded-xl bg-[#FFF9FA] border border-rosepet-soft">
                            <span class="text-[10px] font-bold uppercase text-rosepet-muted block">Panjang Tali</span>
                            <strong class="text-rosepet-dark block mt-0.5">{{ $product->strap_length ?: '-' }}</strong>
                        </div>
                        <div class="p-3 rounded-xl bg-[#FFF9FA] border border-rosepet-soft">
                            <span class="text-[10px] font-bold uppercase text-rosepet-muted block">Kapasitas</span>
                            <strong class="text-rosepet-dark block mt-0.5">{{ $product->capacity_liter ? $product->capacity_liter . ' Liter' : '-' }}</strong>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- ======================================================== -->
    <!-- LIGHTBOX MODAL (IMAGE & 3D MODEL INTERACTIVE FULLSCREEN) -->
    <!-- ======================================================== -->
    <div id="universal-lightbox" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4 transition-all">
        <div class="relative w-full max-w-4xl bg-white rounded-3xl overflow-hidden shadow-2xl border-2 border-rosepet-border flex flex-col max-h-[90vh]">
            
            <!-- Lightbox Top Header -->
            <div class="p-4 px-6 bg-[#FFF9FA] border-b border-rosepet-soft flex items-center justify-between">
                <div>
                    <h4 id="lightbox-title" class="font-serif font-bold text-base text-rosepet-dark">Pratinjau Media</h4>
                    <span id="lightbox-subtitle" class="text-[10px] text-rosepet-muted">Mode Tampilan Detail Layar Penuh</span>
                </div>
                <button type="button" onclick="closeLightbox()" class="w-9 h-9 rounded-full bg-rosepet-soft text-rosepet-dark hover:bg-rosepet-fresh hover:text-white transition-colors flex items-center justify-center font-bold text-base">
                    ✕
                </button>
            </div>

            <!-- Lightbox Body Container -->
            <div class="relative flex-1 bg-[#FAF7F8] flex items-center justify-center p-4 min-h-[480px]">
                
                <!-- View 1: Image Lightbox with Slider Prev/Next Controls -->
                <div id="lightbox-image-view" class="w-full h-full flex items-center justify-center relative">
                    <!-- Tombol Navigasi Sebelumnya (Prev) -->
                    <button type="button" id="lb-btn-prev" onclick="lightboxPrevImage()" class="absolute left-2 sm:left-4 z-20 w-11 h-11 rounded-full bg-white/90 hover:bg-rosepet-fresh text-rosepet-dark hover:text-white shadow-lg border border-rosepet-soft/80 flex items-center justify-center text-lg font-bold transition-all focus:outline-none" title="Foto Sebelumnya">
                        ‹
                    </button>

                    <img id="lightbox-img-el" src="" alt="Detail Tas" class="max-h-[70vh] max-w-full object-contain filter drop-shadow-md transition-all duration-200">

                    <!-- Tombol Navigasi Berikutnya (Next) -->
                    <button type="button" id="lb-btn-next" onclick="lightboxNextImage()" class="absolute right-2 sm:right-4 z-20 w-11 h-11 rounded-full bg-white/90 hover:bg-rosepet-fresh text-rosepet-dark hover:text-white shadow-lg border border-rosepet-soft/80 flex items-center justify-center text-lg font-bold transition-all focus:outline-none" title="Foto Berikutnya">
                        ›
                    </button>

                    <!-- Indikator Halaman Foto & Reset -->
                    <div class="absolute bottom-2 inset-x-0 flex items-center justify-center gap-2 pointer-events-none">
                        <div class="pointer-events-auto px-3 py-1 rounded-full bg-white/90 backdrop-blur-md border border-rosepet-soft shadow-sm flex items-center gap-2 text-xs font-bold text-rosepet-dark">
                            <span id="lb-counter">1 / 1</span>
                            <span class="text-rosepet-muted">•</span>
                            <button type="button" onclick="lightboxResetToFirst()" class="text-rosepet-fresh hover:underline text-[11px]" title="Kembali ke Foto Pertama">
                                ↺ Foto Awal
                            </button>
                        </div>
                    </div>
                </div>

                <!-- View 2: 3D Model Lightbox (Interactive Canvas) -->
                <div id="lightbox-3d-view" class="hidden w-full h-[520px] relative">
                    <canvas id="lightbox-3d-canvas" class="w-full h-full cursor-grab active:cursor-grabbing"></canvas>
                    <div class="absolute top-3 left-3 px-3 py-1.5 rounded-full bg-white/90 backdrop-blur-md text-[10px] font-bold text-rosepet-dark border border-rosepet-soft shadow-sm pointer-events-none">
                        <span>🎮 Gerakkan mouse untuk putar 360°, scroll untuk zoom</span>
                    </div>
                    <button type="button" onclick="resetLightbox3dCamera()" class="absolute bottom-3 right-3 px-3 py-1.5 rounded-xl bg-white/90 backdrop-blur-md text-[10px] font-bold text-rosepet-dark border border-rosepet-soft hover:bg-rosepet-fresh hover:text-white shadow-sm transition-all">
                        Reset Posisi
                    </button>
                    <div id="lightbox-3d-loader" class="absolute inset-0 bg-[#FAF7F8]/80 backdrop-blur-sm flex flex-col items-center justify-center gap-2">
                        <svg class="animate-spin h-6 w-6 text-rosepet-fresh" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span class="text-xs font-bold text-rosepet-dark">Menyiapkan Kanvas 3D Layar Penuh...</span>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-rosepet-border/60 py-8 text-center text-xs text-rosepet-deep">
        <p>© 2026 Mikael On Shop Atelier. Semua hak dilindungi undang-undang.</p>
    </footer>

    <!-- Script Interaktif Switcher & Three.js Lightbox -->
    <script>
        let storeScene, storeCamera, storeRenderer, storeControls, storePivotGroup;
        let isStore3dInit = false;

        let lbScene, lbCamera, lbRenderer, lbControls, lbPivotGroup;
        let isLb3dInit = false;

        function switchDetailMedia(mode) {
            const photoFrame = document.getElementById('detail-photo-frame');
            const d3Frame = document.getElementById('detail-3d-frame');
            const btnPhoto = document.getElementById('btn-show-photo');
            const btn3d = document.getElementById('btn-show-3d');

            if (mode === 'photo') {
                photoFrame.classList.remove('hidden');
                if (d3Frame) d3Frame.classList.add('hidden');
                btnPhoto.className = 'px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all';
                if (btn3d) btn3d.className = 'px-3.5 py-1.5 rounded-xl bg-rosepet-soft text-rosepet-deep hover:bg-rosepet-fresh hover:text-white text-xs font-bold flex items-center gap-1.5 transition-all';
            } else {
                photoFrame.classList.add('hidden');
                if (d3Frame) d3Frame.classList.remove('hidden');
                btnPhoto.className = 'px-3.5 py-1.5 rounded-xl bg-rosepet-soft text-rosepet-deep hover:bg-rosepet-fresh hover:text-white text-xs font-bold flex items-center gap-1.5 transition-all';
                if (btn3d) btn3d.className = 'px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all';

                if (!isStore3dInit) {
                    initStorefront3d();
                } else {
                    handleStoreResize();
                }
                setTimeout(handleStoreResize, 100);
            }
        }

        function selectDetailPhoto(url) {
            const img = document.getElementById('current-detail-img');
            img.style.opacity = '0.3';
            setTimeout(() => {
                img.src = url;
                img.style.opacity = '1';
            }, 150);
            switchDetailMedia('photo');
        }

        // ==========================================
        // LIGHTBOX CONTROLS WITH SLIDER
        // ==========================================
        @php
            $galleryUrls = [];
            foreach($product->images as $imgItem) {
                $galleryUrls[] = asset($imgItem->image_path);
            }
            if(empty($galleryUrls)) {
                $galleryUrls[] = $firstImg;
            }
        @endphp
        const productGalleryPhotos = @json($galleryUrls);
        let currentLbPhotoIndex = 0;

        function updateLightboxImageDisplay() {
            const imgEl = document.getElementById('lightbox-img-el');
            const counterEl = document.getElementById('lb-counter');
            const total = productGalleryPhotos.length;

            if (total === 0) return;

            if (currentLbPhotoIndex < 0) currentLbPhotoIndex = total - 1;
            if (currentLbPhotoIndex >= total) currentLbPhotoIndex = 0;

            imgEl.style.opacity = '0.3';
            setTimeout(() => {
                imgEl.src = productGalleryPhotos[currentLbPhotoIndex];
                imgEl.style.opacity = '1';
            }, 100);

            if (counterEl) {
                counterEl.innerText = `${currentLbPhotoIndex + 1} / ${total}`;
            }

            const btnPrev = document.getElementById('lb-btn-prev');
            const btnNext = document.getElementById('lb-btn-next');
            if (btnPrev && btnNext) {
                if (total <= 1) {
                    btnPrev.classList.add('hidden');
                    btnNext.classList.add('hidden');
                } else {
                    btnPrev.classList.remove('hidden');
                    btnNext.classList.remove('hidden');
                }
            }
        }

        function lightboxNextImage() {
            currentLbPhotoIndex++;
            updateLightboxImageDisplay();
        }

        function lightboxPrevImage() {
            currentLbPhotoIndex--;
            updateLightboxImageDisplay();
        }

        function lightboxResetToFirst() {
            currentLbPhotoIndex = 0;
            updateLightboxImageDisplay();
        }

        function openImageLightbox(imgUrl, title) {
            document.getElementById('lightbox-title').innerText = title;
            document.getElementById('lightbox-subtitle').innerText = 'Foto Resolusi Penuh WebP (Geser / Klik Panah)';
            document.getElementById('lightbox-image-view').classList.remove('hidden');
            document.getElementById('lightbox-3d-view').classList.add('hidden');

            // Cari index foto yang diklik di array galeri
            const matchedIndex = productGalleryPhotos.findIndex(url => url === imgUrl);
            currentLbPhotoIndex = matchedIndex !== -1 ? matchedIndex : 0;
            updateLightboxImageDisplay();

            document.getElementById('universal-lightbox').classList.remove('hidden');
        }

        function open3dLightbox(glbUrl, title) {
            document.getElementById('lightbox-title').innerText = title;
            document.getElementById('lightbox-subtitle').innerText = 'Kanvas Interaktif 3D (360° Free Rotation)';
            document.getElementById('lightbox-image-view').classList.add('hidden');
            document.getElementById('lightbox-3d-view').classList.remove('hidden');

            document.getElementById('universal-lightbox').classList.remove('hidden');

            if (!isLb3dInit) {
                initLightbox3d(glbUrl);
            } else {
                handleLbResize();
            }
            setTimeout(handleLbResize, 150);
        }

        function closeLightbox() {
            document.getElementById('universal-lightbox').classList.add('hidden');
        }

        // Navigasi keyboard panah kiri/kanan & tombol escape untuk lightbox
        document.addEventListener('keydown', function(e) {
            const lb = document.getElementById('universal-lightbox');
            if (lb && !lb.classList.contains('hidden')) {
                if (e.key === 'ArrowRight') {
                    lightboxNextImage();
                } else if (e.key === 'ArrowLeft') {
                    lightboxPrevImage();
                } else if (e.key === 'Escape') {
                    closeLightbox();
                }
            }
        });

        // ==========================================
        // STOREFRONT 3D VIEWPORT
        // ==========================================
        @if($product->model_3d_path)
        function initStorefront3d() {
            const container = document.getElementById('detail-3d-frame');
            const canvas = document.getElementById('detail-storefront-canvas');
            const loaderEl = document.getElementById('store-3d-loader');

            const width = container.clientWidth || 400;
            const height = container.clientHeight || 460;

            storeScene = new THREE.Scene();
            storeScene.background = new THREE.Color(0xFFF7F8);

            storeCamera = new THREE.PerspectiveCamera(40, width / height, 0.1, 100);
            storeCamera.position.set(0, 0.2, 1.8);

            storeRenderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: true });
            storeRenderer.setSize(width, height);
            storeRenderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            storeRenderer.toneMapping = THREE.ACESFilmicToneMapping;
            storeRenderer.toneMappingExposure = 1.15;

            storeControls = new THREE.OrbitControls(storeCamera, storeRenderer.domElement);
            storeControls.enableDamping = true;
            storeControls.dampingFactor = 0.05;
            storeControls.minDistance = 0.8;
            storeControls.maxDistance = 4.0;
            storeControls.maxPolarAngle = Math.PI / 1.7;

            // Pencahayaan Mewah
            storeScene.add(new THREE.AmbientLight(0xFFFFFF, 1.2));
            const keyL = new THREE.DirectionalLight(0xFFF0F5, 1.6);
            keyL.position.set(2, 4, 3);
            storeScene.add(keyL);

            const fillL = new THREE.DirectionalLight(0xEBB4C4, 0.9);
            fillL.position.set(-3, 1, 2);
            storeScene.add(fillL);

            storePivotGroup = new THREE.Group();
            storeScene.add(storePivotGroup);

            const dracoLoader = new THREE.DRACOLoader();
            dracoLoader.setDecoderPath('https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/libs/draco/');

            const gltfLoader = new THREE.GLTFLoader();
            gltfLoader.setDRACOLoader(dracoLoader);

            gltfLoader.load('{{ asset($product->model_3d_path) }}', function (gltf) {
                const model = gltf.scene;
                const box = new THREE.Box3().setFromObject(model);
                const center = box.getCenter(new THREE.Vector3());
                const size = box.getSize(new THREE.Vector3());

                model.position.set(-center.x, -center.y, -center.z);

                const maxDim = Math.max(size.x, size.y, size.z);
                if (maxDim > 0) {
                    const scale = 1.0 / maxDim;
                    storePivotGroup.scale.set(scale, scale, scale);
                }

                storePivotGroup.add(model);
                if (loaderEl) loaderEl.style.display = 'none';
            });

            isStore3dInit = true;
            animateStore();
        }

        function handleStoreResize() {
            if (!storeRenderer || !storeCamera) return;
            const container = document.getElementById('detail-3d-frame');
            if (!container) return;
            const width = container.clientWidth;
            const height = container.clientHeight;
            storeCamera.aspect = width / height;
            storeCamera.updateProjectionMatrix();
            storeRenderer.setSize(width, height);
        }

        function animateStore() {
            requestAnimationFrame(animateStore);
            if (storeControls) storeControls.update();
            if (storePivotGroup) storePivotGroup.rotation.y += 0.003;
            if (storeRenderer && storeScene && storeCamera) {
                storeRenderer.render(storeScene, storeCamera);
            }
        }
        @endif

        // ==========================================
        // LIGHTBOX 3D VIEWPORT
        // ==========================================
        function initLightbox3d(glbUrl) {
            const container = document.getElementById('lightbox-3d-view');
            const canvas = document.getElementById('lightbox-3d-canvas');
            const loaderEl = document.getElementById('lightbox-3d-loader');

            const width = container.clientWidth || 700;
            const height = container.clientHeight || 520;

            lbScene = new THREE.Scene();
            lbScene.background = new THREE.Color(0xFFF7F8);

            lbCamera = new THREE.PerspectiveCamera(40, width / height, 0.1, 100);
            lbCamera.position.set(0, 0.2, 1.8);

            lbRenderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: true });
            lbRenderer.setSize(width, height);
            lbRenderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            lbRenderer.toneMapping = THREE.ACESFilmicToneMapping;
            lbRenderer.toneMappingExposure = 1.2;

            lbControls = new THREE.OrbitControls(lbCamera, lbRenderer.domElement);
            lbControls.enableDamping = true;
            lbControls.dampingFactor = 0.05;

            lbScene.add(new THREE.AmbientLight(0xFFFFFF, 1.3));
            const keyL = new THREE.DirectionalLight(0xFFF0F5, 1.7);
            keyL.position.set(3, 5, 3);
            lbScene.add(keyL);

            lbPivotGroup = new THREE.Group();
            lbScene.add(lbPivotGroup);

            const dracoLoader = new THREE.DRACOLoader();
            dracoLoader.setDecoderPath('https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/libs/draco/');

            const gltfLoader = new THREE.GLTFLoader();
            gltfLoader.setDRACOLoader(dracoLoader);

            gltfLoader.load(glbUrl, function (gltf) {
                const model = gltf.scene;
                const box = new THREE.Box3().setFromObject(model);
                const center = box.getCenter(new THREE.Vector3());
                const size = box.getSize(new THREE.Vector3());

                model.position.set(-center.x, -center.y, -center.z);

                const maxDim = Math.max(size.x, size.y, size.z);
                if (maxDim > 0) {
                    const scale = 1.1 / maxDim;
                    lbPivotGroup.scale.set(scale, scale, scale);
                }

                lbPivotGroup.add(model);
                if (loaderEl) loaderEl.style.display = 'none';
            });

            isLb3dInit = true;
            animateLb();
        }

        function handleLbResize() {
            if (!lbRenderer || !lbCamera) return;
            const container = document.getElementById('lightbox-3d-view');
            if (!container) return;
            const width = container.clientWidth;
            const height = container.clientHeight;
            lbCamera.aspect = width / height;
            lbCamera.updateProjectionMatrix();
            lbRenderer.setSize(width, height);
        }

        function resetLightbox3dCamera() {
            if (!lbControls || !lbCamera) return;
            lbControls.reset();
            lbCamera.position.set(0, 0.2, 1.8);
        }

        function animateLb() {
            requestAnimationFrame(animateLb);
            if (lbControls) lbControls.update();
            if (lbPivotGroup) lbPivotGroup.rotation.y += 0.003;
            if (lbRenderer && lbScene && lbCamera) {
                lbRenderer.render(lbScene, lbCamera);
            }
        }
        let currentUnitPrice = {{ $promoPrice }};
        let currentMaxStock = {{ $product->variants->first()->stock ?? 10 }};

        function updateSelectedVariantPrice(normalPrice, promoPrice, stock) {
            currentUnitPrice = promoPrice;
            currentMaxStock = stock;
            
            const qtyInput = document.getElementById('checkout-qty');
            const stockInfo = document.getElementById('stock-info-text');
            const submitBtn = document.getElementById('btn-submit-order');
            const submitText = document.getElementById('btn-submit-text');

            qtyInput.max = stock;
            if (stock <= 0) {
                qtyInput.value = 0;
                qtyInput.disabled = true;
                stockInfo.innerText = '⚠️ Maaf, stok habis untuk varian ini';
                stockInfo.className = 'text-[10px] text-red-600 font-bold mt-1';
                submitBtn.disabled = true;
                submitText.innerText = 'Stok Habis';
            } else {
                qtyInput.disabled = false;
                submitBtn.disabled = false;
                submitText.innerText = 'Pesan Sekarang';
                if (parseInt(qtyInput.value) <= 0) {
                    qtyInput.value = 1;
                } else if (parseInt(qtyInput.value) > stock) {
                    qtyInput.value = stock;
                }
                stockInfo.innerText = 'Tersedia ' + stock + ' pcs';
                stockInfo.className = 'text-[10px] text-rosepet-muted mt-1';
            }

            validateQuantityInput();
            calculateGrandTotal();
        }

        // Inisialisasi status tombol saat pertama kali halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const activeRadio = document.querySelector('input[name="variant_id"]:checked');
            if (activeRadio) {
                const initStock = parseInt(activeRadio.getAttribute('data-stock')) || 0;
                if (initStock <= 0) {
                    updateSelectedVariantPrice(currentUnitPrice, currentUnitPrice, 0);
                }
            }
        });

        function validateQuantityInput() {
            const qtyInput = document.getElementById('checkout-qty');
            const warningBadge = document.getElementById('stock-warning-badge');
            let val = parseInt(qtyInput.value) || 1;

            if (val > currentMaxStock) {
                qtyInput.value = currentMaxStock;
                warningBadge.classList.remove('hidden');
                setTimeout(() => warningBadge.classList.add('hidden'), 2500);
            } else if (val < 1 && currentMaxStock > 0) {
                qtyInput.value = 1;
            }

            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            const qtyInput = document.getElementById('checkout-qty');
            const courierSelect = document.getElementById('checkout-courier');
            const displayEl = document.getElementById('display-grand-total');
            const shippingStatusEl = document.getElementById('display-shipping-status');
            const courierNoteEl = document.getElementById('courier-note-text');

            const paymentMethodInput = document.querySelector('input[name="payment_method"]:checked');
            const paymentMethod = paymentMethodInput ? paymentMethodInput.value : 'COD';

            const qty = parseInt(qtyInput.value) || 0;
            const selectedOption = courierSelect.options[courierSelect.selectedIndex];
            
            let baseShippingCost = parseInt(selectedOption.getAttribute('data-cost')) || 0;
            if (selectedOption.value !== 'COD-EXPRESS' && baseShippingCost === 0) {
                baseShippingCost = 15000;
            }

            // Aturan Bisnis COD Toko Mikael on Shop: Jika COD maka ongkos kirim Rp 0 (Bebas Ongkir)
            let actualShippingCost = 0;
            if (paymentMethod === 'COD') {
                actualShippingCost = 0;
                shippingStatusEl.innerText = 'Ongkir: Rp 0 (Bebas Ongkir COD)';
                shippingStatusEl.className = 'text-[9px] text-emerald-600 font-bold';
                courierNoteEl.innerText = '✓ Promo COD Toko: Ongkir Rp 0';
                courierNoteEl.className = 'text-[10px] text-emerald-600 font-bold mt-1';
            } else {
                actualShippingCost = baseShippingCost > 0 ? baseShippingCost : 15000;
                shippingStatusEl.innerText = 'Ongkir: Rp ' + actualShippingCost.toLocaleString('id-ID');
                shippingStatusEl.className = 'text-[9px] text-rosepet-muted font-bold';
                courierNoteEl.innerText = 'Ongkir ekspedisi reguler';
                courierNoteEl.className = 'text-[10px] text-rosepet-muted font-medium mt-1';
            }

            const grandTotal = (currentUnitPrice * qty) + actualShippingCost;
            displayEl.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        // --- Fitur Pencarian Destinasi Wilayah Indonesia (RajaOngkir Official Komerce) ---
        let searchTimeout = null;

        function debounceSearchLocation(keyword) {
            clearTimeout(searchTimeout);
            const dropdown = document.getElementById('location-results-dropdown');
            const spinner = document.getElementById('location-spinner');

            if (!keyword || keyword.trim().length < 2) {
                dropdown.classList.add('hidden');
                dropdown.innerHTML = '';
                spinner.classList.add('hidden');
                return;
            }

            spinner.classList.remove('hidden');

            searchTimeout = setTimeout(() => {
                fetch(`/api/locations/search?q=${encodeURIComponent(keyword.trim())}`)
                    .then(res => res.json())
                    .then(data => {
                        spinner.classList.add('hidden');
                        if (!data || data.length === 0) {
                            dropdown.innerHTML = '<div class="p-3 text-xs text-rosepet-muted text-center">Wilayah tidak ditemukan. Coba ketik nama kecamatan/kota lainnya.</div>';
                            dropdown.classList.remove('hidden');
                            return;
                        }

                        let html = '';
                        data.forEach(item => {
                            const labelSafe = (item.label || '').replace(/'/g, "\\'");
                            const provSafe = (item.province_name || '').replace(/'/g, "\\'");
                            const citySafe = (item.city_name || '').replace(/'/g, "\\'");
                            const distSafe = (item.district_name || '').replace(/'/g, "\\'");
                            const subSafe = `${item.subdistrict_name || ''} (${item.zip_code || ''})`.replace(/'/g, "\\'");

                            html += `
                                <div onclick="selectLocation(${item.id}, '${provSafe}', '${citySafe}', '${distSafe}', '${subSafe}')" 
                                     class="p-2.5 px-3 hover:bg-rosepet-soft/50 cursor-pointer transition-colors text-left">
                                    <div class="font-bold text-xs text-rosepet-dark">${item.subdistrict_name || ''}, ${item.district_name || ''}</div>
                                    <div class="text-[10px] text-rosepet-muted">${item.city_name}, ${item.province_name} - ${item.zip_code || ''}</div>
                                </div>
                            `;
                        });

                        dropdown.innerHTML = html;
                        dropdown.classList.remove('hidden');
                    })
                    .catch(err => {
                        spinner.classList.add('hidden');
                        console.error('Location search error:', err);
                    });
            }, 300);
        }

        function selectLocation(id, province, city, district, subdistrict) {
            document.getElementById('input-destination-id').value = id;
            document.getElementById('input-province').value = province;
            document.getElementById('input-city').value = city;
            document.getElementById('input-district').value = district;
            document.getElementById('input-subdistrict').value = subdistrict;

            document.getElementById('location-search-input').value = `${district}, ${city}`;
            document.getElementById('location-results-dropdown').classList.add('hidden');

            // Hitung tarif ongkir real-time untuk destinasi baru
            fetchRealtimeShipping(id);
        }

        function fetchRealtimeShipping(destinationId) {
            const courierSelect = document.getElementById('checkout-courier');
            const courierNote = document.getElementById('courier-note-text');
            courierNote.innerText = 'Mengambil tarif ongkir ekspedisi...';

            fetch('/api/shipping/calculate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    destination_id: destinationId,
                    weight: 1000,
                    courier: 'jne'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (Array.isArray(data) && data.length > 0) {
                    // Update opsi ongkir
                    courierSelect.innerHTML = '';
                    
                    // Tambahkan opsi COD bawaan toko (Gratis Ongkir)
                    const codOpt = document.createElement('option');
                    codOpt.value = 'COD-EXPRESS';
                    codOpt.setAttribute('data-cost', 0);
                    codOpt.selected = true;
                    codOpt.innerText = '🚚 COD Express (Gratis Ongkir)';
                    courierSelect.appendChild(codOpt);

                    data.forEach(srv => {
                        const opt = document.createElement('option');
                        opt.value = `JNE-${srv.service}`;
                        opt.setAttribute('data-cost', srv.cost);
                        opt.innerText = `JNE ${srv.service} (${srv.etd || '1-3'} hari) - Rp ${srv.cost.toLocaleString('id-ID')}`;
                        courierSelect.appendChild(opt);
                    });
                }
                calculateGrandTotal();
            })
            .catch(err => {
                console.error('Shipping calculation error:', err);
                calculateGrandTotal();
            });
        }

        // Tutup dropdown saat klik di luar
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('location-results-dropdown');
            const searchInput = document.getElementById('location-search-input');
            if (dropdown && !dropdown.contains(e.target) && e.target !== searchInput) {
                dropdown.classList.add('hidden');
            }
        });

        // --- Handler Checkout & Pop-up Snap Midtrans ---
        function handleDirectCheckout(e) {
            const form = document.getElementById('direct-checkout-form');
            const paymentMethodInput = document.querySelector('input[name="payment_method"]:checked');
            const paymentMethod = paymentMethodInput ? paymentMethodInput.value : 'COD';

            // Jika metode pembayaran QRIS atau TRANSFER, panggil Midtrans Snap via AJAX
            if (paymentMethod === 'QRIS' || paymentMethod === 'TRANSFER') {
                e.preventDefault();

                const submitBtn = document.getElementById('btn-submit-order');
                const submitText = document.getElementById('btn-submit-text');
                const originalText = submitText.innerText;

                submitBtn.disabled = true;
                submitText.innerText = 'Menyiapkan Pembayaran...';

                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    submitBtn.disabled = false;
                    submitText.innerText = originalText;

                    if (data.snap_token) {
                        // Buka Pop-up Snap Midtrans
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                window.location.href = data.success_url;
                            },
                            onPending: function(result) {
                                window.location.href = data.success_url;
                            },
                            onError: function(result) {
                                alert('Pembayaran gagal atau dibatalkan. Silakan coba kembali.');
                                window.location.href = data.success_url;
                            },
                            onClose: function() {
                                alert('Anda menutup jendela pembayaran sebelum selesai. Anda tetap dapat melanjutkan pembayaran di halaman pesanan.');
                                window.location.href = data.success_url;
                            }
                        });
                    } else if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.error || 'Terjadi kendala saat memproses pesanan.');
                    }
                })
                .catch(err => {
                    submitBtn.disabled = false;
                    submitText.innerText = originalText;
                    console.error('Checkout error:', err);
                    alert('Gagal menghubungi server pembayaran. Mohon periksa koneksi Anda.');
                });
            }
            // Jika COD, biarkan form submit normal langsung ke invoice
        }
    </script>
</body>
</html>
