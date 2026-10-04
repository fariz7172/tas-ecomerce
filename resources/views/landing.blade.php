<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Primary SEO Meta Tags -->
    <title>Mikael on Shop — Koleksi Tas Mewah & Kulit Asli | Official Store</title>
    <meta name="description" content="Official Store Mikael on Shop. Koleksi tas wanita dan pria terbuat dari 100% Genuine Leather. Eksplorasi 3D interaktif 360°, garansi keaslian 100%, COD & asuransi penuh.">
    <meta name="keywords" content="tas wanita mewah, handbag branded, tote bag kulit asli, shoulder bag wanita, tas pesta, official store tas, belanja tas online, mikael on shop, genuine leather bag">
    <link rel="canonical" href="{{ url('/') }}">
    <meta name="robots" content="index, follow, max-image-preview:large">

    <!-- Open Graph / WhatsApp Preview -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Mikael on Shop — Official Leather Store">
    <meta property="og:description" content="Koleksi tas mewah 100% Genuine Leather dengan visual 3D interaktif 360° dan layanan COD & Asuransi Penuh.">
    <meta property="og:image" content="{{ asset('storage/products/hero-og.webp') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Mikael On Shop — Official Luxury Store">
    <meta name="twitter:description" content="Eksplorasi siluet tas kulit mewah dalam kanvas 3D interaktif Three.js.">

    <!-- Google Structured Data: JSON-LD Rich Snippet -->
    @if(isset($jsonLd))
    <script type="application/ld+json">
        {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endif

    <!-- Cloudflare & Browser Early Preloading -->
    <link rel="preload" href="/models/lv.glb" as="fetch" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,900;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        rosepet: {
                            base: '#FFF5F7',
                            glow: '#FEECEF',
                            soft: '#FCD8E2',
                            fresh: '#E8617D',
                            vibrant: '#EC407A',
                            deep: '#C24765',
                            dark: '#261218',
                            muted: '#7A646C',
                            border: '#EBB4C4',
                        }
                    },
                    boxShadow: {
                        'card-pop': '0 10px 30px -5px rgba(232, 97, 125, 0.12), 0 4px 12px -2px rgba(38, 18, 24, 0.05)',
                        'card-hover': '0 20px 40px -8px rgba(232, 97, 125, 0.22), 0 8px 18px -3px rgba(38, 18, 24, 0.08)',
                    }
                }
            }
        }
    </script>
    
    <!-- Three.js + GLTFLoader & DRACOLoader & OrbitControls CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/DRACOLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

    <style>
        body {
            background-color: #FFF5F7;
            color: #261218;
        }
        .hero-banner-bg {
            background: linear-gradient(135deg, #FFF0F3 0%, #FEE8EE 50%, #FDDCE4 100%);
        }
        .distinct-card {
            background: #FFFFFF;
            border: 2px solid #EBB4C4;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .distinct-card:hover {
            border-color: #E8617D;
            transform: translateY(-4px);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 7px; }
        ::-webkit-scrollbar-track { background: #FFF5F7; }
        ::-webkit-scrollbar-thumb { background: #FCD8E2; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #E8617D; }
    </style>
</head>
<body class="font-sans antialiased selection:bg-rosepet-fresh selection:text-white">

    <!-- TOP ANNOUNCEMENT BAR (ALA TOKOPEDIA TOP BAR) -->
    <div class="bg-gradient-to-r from-rosepet-deep via-rosepet-fresh to-rosepet-vibrant text-white text-[11px] font-semibold py-2 px-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="bg-white/20 px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider">Official Store</span>
                <span class="hidden sm:inline">Gratis Ongkir Seluruh Indonesia • Garansi Kulit Asli 100% • Bebas Pengembalian 14 Hari</span>
                <span class="sm:hidden">Gratis Ongkir & Garansi Resmi 100%</span>
            </div>
            <div class="flex items-center gap-4 text-[10px] font-medium">
                <a href="#katalog" class="hover:underline">Promo Spesial</a>
                @auth
                    @if(Auth::user()->role === 'admin')
                        <span class="opacity-50">|</span>
                        <a href="{{ route('admin.dashboard') }}" class="hover:underline flex items-center gap-1 text-amber-200 font-bold">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Admin Panel
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- MAIN NAVBAR WITH SEARCH & CATEGORY BAR -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b-2 border-rosepet-soft/80 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between gap-6">
            
            <!-- Brand Logo -->
            <a href="/" class="flex items-center gap-3.5 flex-shrink-0 group">
                <img src="{{ asset('assets/logo.png') }}" alt="Mikael on Shop" class="w-14 h-14 sm:w-16 sm:h-16 object-contain group-hover:scale-105 transition-transform drop-shadow-md">
                <div>
                    <span class="font-serif font-black text-2xl sm:text-3xl tracking-[0.14em] uppercase text-rosepet-dark block leading-none">Mikael <span class="font-normal text-rosepet-fresh italic">on Shop</span></span>
                    <span class="text-[10px] uppercase tracking-widest text-rosepet-muted font-bold block mt-1.5">Official Leather Store</span>
                </div>
            </a>

            <!-- Search Bar (E-Commerce Style Tokopedia) -->
            <form action="{{ route('home') }}#katalog" method="GET" class="hidden md:flex flex-1 max-w-xl relative">
                @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('brand'))
                <input type="hidden" name="brand" value="{{ request('brand') }}">
                @endif
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari tas pesta, tote bag kulit, handbag, shoulder bag..." class="w-full bg-[#FFF5F7] rounded-2xl pl-11 pr-10 py-2.5 text-xs text-rosepet-dark border-2 border-rosepet-soft focus:border-rosepet-fresh focus:bg-white outline-none transition-all placeholder:text-rosepet-muted font-medium">
                <button type="submit" class="absolute left-4 top-3 text-rosepet-muted hover:text-rosepet-fresh transition-colors" title="Cari">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
                @if(request('q'))
                <a href="{{ route('home', request()->except('q')) }}#katalog" class="absolute right-3 top-2.5 text-xs text-gray-400 hover:text-rose-600 font-bold p-1" title="Hapus Pencarian">✕</a>
                @endif
            </form>

            <!-- Quick Navigation, Wishlist & Auth -->
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ route('wishlist.index') }}" class="p-2.5 px-3.5 rounded-2xl bg-white border-2 border-rosepet-border hover:border-rosepet-fresh text-rose-600 transition-all shadow-sm flex items-center gap-1.5 text-xs font-bold" title="Wishlist Saya">
                    <span>♥</span>
                    <span class="hidden sm:inline">Wishlist</span>
                </a>

                @auth
                <div class="flex items-center gap-2">
                    <a href="{{ route('orders.history') }}" class="p-2.5 px-3.5 rounded-2xl bg-white border-2 border-rosepet-border hover:border-rosepet-fresh text-rosepet-dark transition-all shadow-sm flex items-center gap-1.5 text-xs font-bold" title="Riwayat Pesanan">
                        <span>🛍️</span>
                        <span class="hidden sm:inline">Pesanan</span>
                    </a>
                    <a href="{{ route('orders.history') }}" class="hidden md:block text-xs font-semibold text-rosepet-dark hover:text-rosepet-fresh">
                        Halo, <strong>{{ Auth::user()->name }}</strong>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3.5 py-2 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition-all border border-rose-200">
                            Keluar
                        </button>
                    </form>
                </div>
                @else
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-2xl bg-white border-2 border-rosepet-border hover:border-rosepet-fresh text-rosepet-dark hover:text-rosepet-fresh text-xs font-bold transition-all shadow-sm">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="hidden sm:inline-flex px-4 py-2 rounded-2xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white text-xs font-bold hover:shadow-md transition-all">
                    Daftar
                </a>
                @endauth
            </div>
        </div>

        <!-- Quick Category Chips Bar -->
        <div class="border-t border-rosepet-soft/60 px-6 py-2.5 bg-white/70 overflow-x-auto">
            <div class="max-w-7xl mx-auto flex items-center gap-2.5 text-xs font-bold text-rosepet-muted">
                <span class="text-[11px] uppercase tracking-wider text-rosepet-dark font-extrabold flex-shrink-0">Kategori:</span>
                <a href="{{ route('home') }}#katalog" class="px-3.5 py-1 rounded-full transition-all flex-shrink-0 {{ !request('category') && !request('brand') ? 'bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white shadow-sm' : 'bg-rosepet-soft/60 text-rosepet-deep hover:bg-rosepet-fresh hover:text-white' }}">
                    Semua Tas
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('home', ['category' => $cat->slug]) }}#katalog" class="px-3.5 py-1 rounded-full transition-all flex-shrink-0 flex items-center gap-1.5 {{ request('category') === $cat->slug ? 'bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white shadow-sm' : 'bg-white border border-rosepet-border hover:border-rosepet-fresh text-rosepet-dark' }}">
                    <span>{{ $cat->name }}</span>
                    <span class="text-[10px] opacity-75">({{ $cat->products_count }})</span>
                </a>
                @endforeach

                <div class="h-4 w-px bg-rosepet-border mx-1 flex-shrink-0"></div>

                <span class="text-[11px] uppercase tracking-wider text-rosepet-dark font-extrabold flex-shrink-0">Merk:</span>
                @foreach($brands as $br)
                @if($br->products_count > 0 || in_array($br->name, ['Louis Vuitton', 'CHARLES & KEITH', 'Chanel', 'Hermes']))
                <a href="{{ route('home', ['brand' => $br->slug]) }}#katalog" class="px-3 py-1 rounded-full transition-all flex-shrink-0 flex items-center gap-1.5 {{ request('brand') === $br->slug ? 'bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white shadow-sm' : 'bg-white border border-rosepet-border hover:border-rosepet-fresh text-rosepet-dark' }}">
                    <span>{{ $br->name }}</span>
                    @if($br->products_count > 0)
                    <span class="text-[10px] opacity-75">({{ $br->products_count }})</span>
                    @endif
                </a>
                @endif
                @endforeach
            </div>
        </div>
    </header>

    <!-- HERO SECTION: MODERN E-COMMERCE BANNER DENGAN 3D MODEL SEBAGAI PEMANIS VISUAL -->
    <section class="max-w-7xl mx-auto px-6 py-8">
        <div class="hero-banner-bg rounded-[3rem] border-3 border-rosepet-border p-8 sm:p-14 shadow-card-pop relative overflow-hidden">
            
            <!-- Background Decorative Blur Circle -->
            <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-rosepet-soft/60 blur-3xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                
                <!-- SISI KIRI: Headline Penjualan & Promosi Ala Tokopedia Official Store (Col 7) -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border-2 border-rosepet-border text-xs font-black uppercase tracking-wider text-rosepet-deep shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-rosepet-fresh animate-pulse"></span>
                        Official Grand Launching Collection 2026
                    </div>

                    <h1 class="font-serif text-4xl sm:text-6xl font-black text-rosepet-dark tracking-tight leading-[1.12]">
                        Sentuhan Mewah, <br>
                        <span class="text-rosepet-fresh italic font-normal">Karakter Abadi</span> Untuk Setiap Gaya Anda.
                    </h1>

                    <p class="text-xs sm:text-sm text-rosepet-muted leading-relaxed font-medium max-w-xl">
                        Temukan koleksi tas wanita & pria mewah dengan standar kulit sapi pilihan Italia. Dirancang dengan presisi jahitan tangan dan aksesoris logam lapis emas 18K untuk menemani setiap momen istimewa Anda.
                    </p>

                    <!-- Trust Stats & Badges Strip -->
                    <div class="pt-2 flex flex-wrap items-center gap-6 text-xs text-rosepet-dark font-bold">
                        <div class="flex items-center gap-2">
                            <span class="text-rosepet-fresh text-base">★</span>
                            <span>4.9 / 5.0 Ulasan Pembeli</span>
                        </div>
                        <div class="h-3 w-px bg-rosepet-border"></div>
                        <div class="flex items-center gap-2">
                            <span class="text-rosepet-fresh text-base">✓</span>
                            <span>Garansi Kulit Asli 100%</span>
                        </div>
                        <div class="h-3 w-px bg-rosepet-border"></div>
                        <div class="flex items-center gap-2">
                            <span class="text-rosepet-fresh text-base">🚀</span>
                            <span>Gratis Ongkir Nasional</span>
                        </div>
                    </div>

                    <!-- Call To Action Buttons -->
                    <div class="pt-4 flex flex-wrap items-center gap-4">
                        <a href="#katalog" class="px-8 py-4 rounded-2xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-xs uppercase tracking-wider hover:shadow-xl hover:shadow-rosepet-fresh/35 transition-all flex items-center gap-2 group">
                            <span>Jelajahi Katalog Tas</span>
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                        <a href="#tokopedia-editorial" class="px-6 py-4 rounded-2xl bg-white hover:bg-rosepet-soft/50 text-rosepet-dark font-bold text-xs uppercase tracking-wider border-2 border-rosepet-border transition-all">
                            Tentang Mikael On Shop
                        </a>
                    </div>
                </div>

                <!-- SISI KANAN: 3D MODEL THREE.JS SEBAGAI PEMANIS VISUAL INTERAKTIF (Col 5) -->
                <div class="lg:col-span-5 relative flex items-center justify-center">
                    
                    <!-- 3D Canvas Box (Pemanis Tampilan Bersih & Elegan, Tanpa Box Spesifikasi) -->
                    <div class="relative w-full h-[380px] sm:h-[450px] rounded-[2.5rem] bg-white/70 backdrop-blur-md border-2 border-rosepet-border shadow-card-pop overflow-hidden group">
                        
                        <!-- Canvas Three.js Element -->
                        <div id="three-bag-container" class="w-full h-full cursor-grab active:cursor-grabbing relative">
                            <!-- Preloader Spinner -->
                            <div id="canvas-loading" class="absolute inset-0 flex flex-col items-center justify-center bg-white/90 z-20 transition-opacity duration-500">
                                <div class="w-10 h-10 border-3 border-rosepet-soft border-t-rosepet-fresh rounded-full animate-spin mb-3"></div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-rosepet-deep">Memuat Model 3D...</span>
                            </div>

                            <!-- Floating Interactive Hint Badge -->
                            <div class="absolute top-4 left-4 z-10 pointer-events-none">
                                <span class="px-3 py-1.5 rounded-xl bg-white/90 backdrop-blur-md text-[10px] font-bold text-rosepet-dark border border-rosepet-border flex items-center gap-1.5 shadow-sm">
                                    <span>🔄</span>
                                    <span>3D Louis Vuitton Interactive (Putar 360°)</span>
                                </span>
                            </div>

                            <!-- Floating Texture Indicator -->
                            <div class="absolute bottom-4 inset-x-4 z-10 flex items-center justify-between p-2.5 rounded-2xl bg-white/90 backdrop-blur-md border border-rosepet-border shadow-sm">
                                <div class="flex items-center gap-2 pl-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-rosepet-dark">Authentic Monogram Texture</span>
                                </div>
                                <span class="text-[10px] font-bold text-rosepet-fresh bg-rosepet-soft px-2.5 py-1 rounded-lg">PBR 2K Optimized</span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- 4 PILAR JAMINAN BELANJA RESMI ALA TOKOPEDIA -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="p-4 rounded-2xl bg-white border-2 border-rosepet-border flex items-center gap-3.5 shadow-sm">
                <span class="w-10 h-10 rounded-xl bg-rosepet-soft text-rosepet-fresh flex items-center justify-center text-xl flex-shrink-0">🛡️</span>
                <div>
                    <h4 class="font-bold text-xs text-rosepet-dark">100% Genuine Leather</h4>
                    <p class="text-[10px] text-rosepet-muted">Garansi keaslian kulit 100%</p>
                </div>
            </div>
            <div class="p-4 rounded-2xl bg-white border-2 border-rosepet-border flex items-center gap-3.5 shadow-sm">
                <span class="w-10 h-10 rounded-xl bg-rosepet-soft text-rosepet-fresh flex items-center justify-center text-xl flex-shrink-0">🚚</span>
                <div>
                    <h4 class="font-bold text-xs text-rosepet-dark">COD & Asuransi Penuh</h4>
                    <p class="text-[10px] text-rosepet-muted">Bayar di tempat & aman terlindungi</p>
                </div>
            </div>
            <div class="p-4 rounded-2xl bg-white border-2 border-rosepet-border flex items-center gap-3.5 shadow-sm">
                <span class="w-10 h-10 rounded-xl bg-rosepet-soft text-rosepet-fresh flex items-center justify-center text-xl flex-shrink-0">🔄</span>
                <div>
                    <h4 class="font-bold text-xs text-rosepet-dark">14 Hari Pengembalian</h4>
                    <p class="text-[10px] text-rosepet-muted">Tukar ukuran & model tanpa ribet</p>
                </div>
            </div>
            <div class="p-4 rounded-2xl bg-white border-2 border-rosepet-border flex items-center gap-3.5 shadow-sm">
                <span class="w-10 h-10 rounded-xl bg-rosepet-soft text-rosepet-fresh flex items-center justify-center text-xl flex-shrink-0">💳</span>
                <div>
                    <h4 class="font-bold text-xs text-rosepet-dark">Pembayaran Fleksibel</h4>
                    <p class="text-[10px] text-rosepet-muted">QRIS, Transfer Bank & COD</p>
                </div>
            </div>
        </div>
    </section>

    <!-- KATALOG PRODUK TAS UTAMA (DENGAN OUTLINE TEBAL JELAS & DETAIL DESKRIPSI LENGKAP ALA TOKOPEDIA) -->
    <main id="katalog" class="max-w-7xl mx-auto px-6 py-16 space-y-12">
        
        <!-- Section Header & Filter Toolbar -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b-2 border-rosepet-border pb-6">
            <div>
                <span class="text-xs font-black uppercase tracking-widest text-rosepet-fresh">Official Catalog</span>
                <h2 class="font-serif text-3xl sm:text-5xl font-black text-rosepet-dark mt-1">Daftar Produk Tas Pilihan</h2>
                @if(request('q'))
                <div class="mt-2 flex items-center gap-2 text-xs">
                    <span class="text-rosepet-muted">Hasil pencarian untuk:</span>
                    <span class="px-2.5 py-0.5 rounded-lg bg-rosepet-soft text-rosepet-deep font-bold font-mono">"{{ request('q') }}"</span>
                    <a href="{{ route('home', request()->except('q')) }}#katalog" class="text-rose-600 hover:underline font-bold text-[11px] ml-1">✕ Reset Pencarian</a>
                </div>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-rosepet-muted font-bold">Total: <strong class="text-rosepet-dark">{{ $products->count() }} Koleksi</strong></span>
                <div class="h-4 w-px bg-rosepet-border"></div>
                <span class="text-xs text-emerald-600 font-bold bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">● Semua Stok Siap Kirim</span>
            </div>
        </div>

        <!-- PRODUCT GRID (DISTINCT CARDS DENGAN BORDER TEBAL & SHADOW KONTRAS) -->
        @if($products->isEmpty())
        <div class="py-16 text-center space-y-4 bg-white/70 backdrop-blur-sm rounded-[2.5rem] border-2 border-dashed border-rosepet-border p-12">
            <span class="text-5xl block">🔍</span>
            <h3 class="font-serif text-2xl font-bold text-rosepet-dark">Tas yang Anda cari belum ditemukan</h3>
            <p class="text-xs text-rosepet-muted max-w-md mx-auto">
                Tidak ada koleksi tas yang cocok dengan kata kunci <strong class="text-rosepet-dark">"{{ request('q') }}"</strong>. Coba periksa ejaan atau telusuri kategori tas lainnya.
            </p>
            <div class="pt-2">
                <a href="{{ route('home') }}#katalog" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-xs shadow-md hover:shadow-lg transition-all">
                    <span>Lihat Semua Koleksi Tas</span>
                    <span>→</span>
                </a>
            </div>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
            @foreach($products as $product)
            @php
                $mainVariant = $product->variants->first();
                $displayImage = $product->images->first()?->image_path ? asset($product->images->first()->image_path) : ($mainVariant?->image_path ?? 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80');
                $price = $mainVariant?->price ?? 389000;
                $promoPrice = $mainVariant?->promo_price ?? $price;
                $discountPercent = $price > $promoPrice ? round((($price - $promoPrice) / $price) * 100) : 0;
                $totalStock = $product->variants->sum('stock');
                $isSoldOut = $totalStock <= 0;
            @endphp
            <div class="distinct-card rounded-[2.5rem] p-6 flex flex-col justify-between h-full shadow-card-pop group relative overflow-hidden {{ $isSoldOut ? 'bg-[#FAF8F9]' : '' }}">
                
                <!-- Badge Official Store & Promo -->
                <div class="flex items-center justify-between mb-4 relative z-10">
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $isSoldOut ? 'bg-gray-200 text-gray-700' : 'bg-rosepet-soft text-rosepet-deep' }} border border-rosepet-border">
                        {{ $product->category->name }}
                    </span>
                    @if($isSoldOut)
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-600 text-white shadow-sm uppercase tracking-wider">
                        Habis Terjual
                    </span>
                    @elseif($discountPercent > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rosepet-fresh text-white shadow-sm">
                        Hemat {{ $discountPercent }}%
                    </span>
                    @endif
                </div>

                <div class="flex-1 flex flex-col">
                    <!-- Interactive Card Media Slider (Photo Carousel & 3D Interactive WebGL) -->
                    <div class="relative w-full h-72 rounded-2xl overflow-hidden bg-[#FAF7F8] mb-6 border-2 border-rosepet-soft/60 group/slider flex-shrink-0" id="slider-card-{{ $product->id }}">
                        
                        @php
                            $has3D = !empty($product->model_3d_path);
                        @endphp

                        <!-- Slide 1: Photo View -->
                        <div id="slide-photo-{{ $product->id }}" class="{{ $has3D ? 'hidden' : '' }} w-full h-full flex items-center justify-center p-2 relative">
                            <a href="{{ route('product.detail', $product->slug) }}" class="w-full h-full flex items-center justify-center">
                                <img id="slide-img-{{ $product->id }}" src="{{ $displayImage }}" 
                                     alt="{{ $product->name }} - {{ $product->material }}" 
                                     class="w-full h-full object-contain group-hover/slider:scale-105 transition-transform duration-500">
                            </a>

                            <!-- Dots Indicator jika ada multi foto -->
                            @if($product->images->count() > 1)
                            <div class="absolute bottom-2.5 left-1/2 -translate-x-1/2 flex items-center gap-1.5 px-2 py-1 rounded-full bg-black/20 backdrop-blur-sm z-10">
                                @foreach($product->images as $imgIdx => $img)
                                <button type="button" onclick="setCardPhotoSlide({{ $product->id }}, '{{ asset($img->image_path) }}', {{ $imgIdx }})" class="w-2 h-2 rounded-full transition-all {{ $imgIdx === 0 ? 'bg-white scale-125' : 'bg-white/50 hover:bg-white' }} dot-{{ $product->id }}"></button>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <!-- Slide 2: 3D Model View (Default Aktif jika tersedia model 3D) -->
                        @if($has3D)
                        <div id="slide-3d-{{ $product->id }}" class="w-full h-full relative bg-gradient-to-b from-[#FFF5F7] to-[#FDF0F3]" data-glb-url="{{ asset($product->model_3d_path) }}">
                            <canvas id="canvas-card-3d-{{ $product->id }}" class="w-full h-full cursor-grab active:cursor-grabbing"></canvas>
                            
                            <div class="absolute top-2 left-2 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-md text-[9px] font-bold text-rosepet-dark border border-rosepet-soft pointer-events-none shadow-sm flex items-center gap-1 z-10">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Model 3D Interaktif</span>
                            </div>

                            <div id="loader-card-3d-{{ $product->id }}" class="absolute inset-0 bg-[#FFF5F7]/80 backdrop-blur-sm flex flex-col items-center justify-center gap-1.5 z-10">
                                <svg class="animate-spin h-5 w-5 text-rosepet-fresh" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span class="text-[10px] font-bold text-rosepet-dark">Memuat 3D...</span>
                            </div>
                        </div>
                        @endif

                        <!-- Slider Toggle Controls (Top Right Overlay) -->
                        <div class="absolute top-3 right-3 flex items-center gap-1 z-20">
                            @if($has3D)
                            <button type="button" onclick="switchCardSlide({{ $product->id }}, '3d', '{{ asset($product->model_3d_path) }}')" id="btn-card-3d-{{ $product->id }}" class="px-2.5 py-1 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white text-[10px] font-bold shadow-sm border border-transparent transition-all flex items-center gap-1" title="Tampilkan Model 3D">
                                <span>🎮 3D</span>
                            </button>
                            @endif
                            <button type="button" onclick="switchCardSlide({{ $product->id }}, 'photo')" id="btn-card-photo-{{ $product->id }}" class="px-2.5 py-1 rounded-xl {{ $has3D ? 'bg-white/80 text-rosepet-deep' : 'bg-white/95 text-rosepet-dark' }} text-[10px] font-bold shadow-sm border border-rosepet-soft hover:bg-rosepet-fresh hover:text-white transition-all flex items-center gap-1" title="Tampilkan Foto">
                                <span>📷 Foto</span>
                            </button>
                        </div>

                    </div>

                    <!-- Title & Comprehensive Description (Ala Tokopedia) -->
                    <div class="space-y-3 flex-1 flex flex-col justify-between">
                        <div>
                            <a href="{{ route('product.detail', $product->slug) }}">
                                <h3 class="font-serif text-xl font-bold text-rosepet-dark hover:text-rosepet-fresh transition-colors leading-snug line-clamp-2 min-h-[3.25rem]">
                                    {{ $product->name }}
                                </h3>
                            </a>

                            <!-- Price Section (Tokopedia Format) -->
                            <div class="pt-1">
                                <div class="font-serif font-black text-2xl text-rosepet-dark">
                                    Rp {{ number_format($promoPrice, 0, ',', '.') }}
                                </div>
                                @if($discountPercent > 0)
                                <div class="flex items-center gap-2 mt-0.5 text-xs text-rosepet-muted">
                                    <span class="line-through">Rp {{ number_format($price, 0, ',', '.') }}</span>
                                    <span class="text-rosepet-fresh font-bold text-[11px] bg-rose-50 px-1.5 py-0.5 rounded">Diskon {{ $discountPercent }}%</span>
                                </div>
                                @else
                                <div class="h-5"></div>
                                @endif
                            </div>

                            <!-- Full Deskripsi Produk & Material (Detail Padat & Jelas) -->
                            <div class="text-xs text-rosepet-muted leading-relaxed line-clamp-3 mt-2 min-h-[3.75rem]">
                                {!! $product->description !!}
                            </div>
                        </div>

                        <div class="space-y-3 pt-3">
                            <!-- Box Spesifikasi Lengkap Produk (Tokopedia Technical Specs Table) -->
                            <div class="bg-[#FFF5F7] rounded-2xl p-4 border border-rosepet-border space-y-2 text-[11px] relative overflow-hidden">
                                @if($isSoldOut)
                                <!-- WATERMARK STAMP SOLD OUT (DI DALAM BOX SPESIFIKASI PRODUK) -->
                                <div class="absolute inset-0 z-20 pointer-events-none flex items-center justify-center bg-white/40 backdrop-blur-[1.5px]">
                                    <div class="w-[120%] py-2.5 bg-gradient-to-r from-rose-700/95 via-red-600/95 to-rose-700/95 text-white font-serif font-black text-sm sm:text-base tracking-[0.25em] uppercase text-center transform -rotate-6 shadow-xl border-y-2 border-white flex items-center justify-center gap-2">
                                        <span class="text-[10px] opacity-80">✦</span>
                                        <span>BARANG SOLD OUT</span>
                                        <span class="text-[10px] opacity-80">✦</span>
                                    </div>
                                </div>
                                @endif

                                <div class="font-bold text-rosepet-dark text-[10px] uppercase tracking-wider pb-1 border-b border-rosepet-soft flex items-center justify-between">
                                    <span>Spesifikasi Produk</span>
                                    <span class="text-rosepet-fresh">Tas Murni</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-rosepet-dark">
                                    <div>
                                        <span class="text-rosepet-muted block text-[10px]">Bahan / Material:</span>
                                        <strong class="font-semibold truncate block" title="{{ $product->material }}">{{ $product->material }}</strong>
                                    </div>
                                    <div>
                                        <span class="text-rosepet-muted block text-[10px]">Dimensi (P x L x T):</span>
                                        <strong class="font-semibold truncate block" title="{{ $product->dimensions_cm }}">{{ $product->dimensions_cm }}</strong>
                                    </div>
                                    <div>
                                        <span class="text-rosepet-muted block text-[10px]">Berat Kosong:</span>
                                        <strong class="font-semibold">{{ $product->weight_grams }} gram</strong>
                                    </div>
                                    <div>
                                        <span class="text-rosepet-muted block text-[10px]">Tipe Pengunci:</span>
                                        <strong class="font-semibold truncate block" title="{{ $product->closure_type }}">{{ $product->closure_type }}</strong>
                                    </div>
                                </div>
                                @if($product->strap_length && $product->strap_length !== '-')
                                <div class="pt-1 border-t border-rosepet-soft text-[10px]">
                                    <span class="text-rosepet-muted">Tali Bahu: </span>
                                    <strong class="text-rosepet-dark truncate inline-block max-w-[200px] align-bottom">{{ $product->strap_length }}</strong>
                                </div>
                                @endif
                            </div>

                            <!-- Variant Color Badges & Stock -->
                            <div class="pt-1 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-bold text-rosepet-muted mr-1">Varian:</span>
                                    @foreach($product->variants as $var)
                                    <span class="w-4 h-4 rounded-full border-2 border-white shadow-sm flex-shrink-0" style="background-color: {{ $var->color_hex }}" title="{{ $var->color_name }}"></span>
                                    @endforeach
                                </div>
                                @if($isSoldOut)
                                <span class="text-[11px] font-bold text-rose-600 bg-rose-50 px-2.5 py-0.5 rounded-md border border-rose-200">
                                    ❌ Stok Habis
                                </span>
                                @else
                                <span class="text-[11px] font-bold text-emerald-700">
                                    Stok: {{ $totalStock }} pcs
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Action Button (Konsultasi / Beli via WhatsApp Official) -->
                <div class="pt-6 mt-6 border-t-2 border-rosepet-soft flex items-center gap-3 relative z-10">
                    <a href="{{ route('product.detail', $product->slug) }}" class="px-4 py-3.5 rounded-2xl bg-rosepet-soft hover:bg-rosepet-border text-rosepet-deep font-bold text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-1.5" title="Lihat Detail Produk">
                        <svg class="w-4 h-4 text-rosepet-fresh" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Detail</span>
                    </a>

                    @if($isSoldOut)
                    <button disabled class="flex-1 py-3.5 rounded-2xl bg-gray-200 text-gray-500 font-bold text-xs uppercase tracking-wider cursor-not-allowed flex items-center justify-center gap-2">
                        <span>❌ Stok Habis</span>
                    </button>
                    @else
                    @php
                        $waText = urlencode("Halo Mikael on Shop, saya tertarik untuk memesan tas:\n*{$product->name}*\nHarga: Rp " . number_format($promoPrice, 0, ',', '.') . "\nMohon info ketersediaan stok & pengiriman COD. Terima kasih!");
                        $waUrl = "https://wa.me/6281288992211?text={$waText}";
                    @endphp
                    <a href="{{ $waUrl }}" target="_blank" class="flex-1 py-3.5 rounded-2xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant hover:shadow-lg hover:shadow-rosepet-fresh/30 text-white font-bold text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2">
                        <span>Pesan via WhatsApp</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    @endif
                </div>

            </div>
            @endforeach
        </div>
        @endif

    </main>

    <!-- SECTION TOKOPEDIA STYLE: BENEFIT ICONS & DESKRIPSI RESMI E-COMMERCE INDONESIA -->
    <section id="tokopedia-editorial" class="max-w-7xl mx-auto px-6 py-12 border-t-2 border-rosepet-border space-y-12">
        
        <!-- GRID BENEFIT DENGAN ICON CANTIK & DESKRIPSI DETAIL (ALA TOKOPEDIA) -->
        <div>
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs font-black uppercase tracking-widest text-rosepet-fresh">Official Buyer Protection</span>
                <h3 class="font-serif text-2xl sm:text-3xl font-bold text-rosepet-dark mt-1">Keunggulan Belanja Tas di Mikael On Shop</h3>
                <p class="text-xs text-rosepet-muted mt-2">Standar pelayanan butik mewah Eropa kini hadir dengan kemudahan transaksi online di Indonesia.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Benefit 1 -->
                <div class="p-6 rounded-3xl bg-white border-2 border-rosepet-border shadow-card-pop space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#FFF5F7] border border-rosepet-border text-rosepet-fresh flex items-center justify-center text-2xl shadow-sm">
                        👜
                    </div>
                    <div class="space-y-2">
                        <h4 class="font-serif font-bold text-base text-rosepet-dark">100% Genuine Leather</h4>
                        <p class="text-xs text-rosepet-muted leading-relaxed">
                            Setiap tas dirancang dari kulit asli berkualitas tinggi (*Genuine Leather*) yang semakin berkilau alami (*patina*) seiring waktu, dijahit manual oleh pengrajin berpengalaman dengan standar mutu presisi.
                        </p>
                    </div>
                    <div class="pt-2 border-t border-rosepet-soft/60 flex items-center gap-2 text-[11px] font-bold text-rosepet-deep">
                        <span>Sertifikat Otentisitas Kulit</span>
                        <span>→</span>
                    </div>
                </div>

                <!-- Benefit 2 -->
                <div class="p-6 rounded-3xl bg-white border-2 border-rosepet-border shadow-card-pop space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#FFF5F7] border border-rosepet-border text-rosepet-fresh flex items-center justify-center text-2xl shadow-sm">
                        🚚
                    </div>
                    <div class="space-y-2">
                        <h4 class="font-serif font-bold text-base text-rosepet-dark">COD & Asuransi Penuh</h4>
                        <p class="text-xs text-rosepet-muted leading-relaxed">
                            Nikmati kemudahan layanan bayar di tempat (COD) ke seluruh kota di Indonesia melalui kemitraan kurir terpercaya JNE, SiCepat, dan kurir kilat dengan proteksi garansi barang sampai utuh dan asuransi penuh.
                        </p>
                    </div>
                    <div class="pt-2 border-t border-rosepet-soft/60 flex items-center gap-2 text-[11px] font-bold text-rosepet-deep">
                        <span>Layanan Bayar di Tempat (COD)</span>
                        <span>→</span>
                    </div>
                </div>

                <!-- Benefit 3 -->
                <div class="p-6 rounded-3xl bg-white border-2 border-rosepet-border shadow-card-pop space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#FFF5F7] border border-rosepet-border text-rosepet-fresh flex items-center justify-center text-2xl shadow-sm">
                        🛡️
                    </div>
                    <div class="space-y-2">
                        <h4 class="font-serif font-bold text-base text-rosepet-dark">Jaminan Tukar & Retur 14 Hari</h4>
                        <p class="text-xs text-rosepet-muted leading-relaxed">
                            Beli tas tanpa ragu. Jika ukuran, tali strap, atau warna kulit tidak sesuai dengan ekspektasi gaya Anda, layanan *VIP Concierge* kami siap memproses penukaran produk atau garansi pengembalian dana dalam 14 hari kerja.
                        </p>
                    </div>
                    <div class="pt-2 border-t border-rosepet-soft/60 flex items-center gap-2 text-[11px] font-bold text-rosepet-deep">
                        <span>Proses Klaim Mudah & Cepat</span>
                        <span>→</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- SEO LONG-FORM EDITORIAL & MARKETPLACE DESCRIPTION (PERSIS POLA TOKOPEDIA FOOTER CONTENT) -->
        <div class="bg-white rounded-3xl p-8 sm:p-10 border-2 border-rosepet-border shadow-card-pop space-y-6">
            
            <div class="border-b border-rosepet-soft pb-4">
                <h3 class="font-serif text-xl sm:text-2xl font-bold text-rosepet-dark">
                    Nikmati Mudahnya Belanja Tas Mewah Online di Mikael On Shop Indonesia
                </h3>
            </div>

            <div class="space-y-4 text-xs sm:text-[13px] text-rosepet-muted leading-relaxed">
                <p>
                    <strong>Mikael On Shop</strong> merupakan official luxury store dan atelier tas kulit premium di Indonesia yang berkomitmen menghadirkan pengalaman belanja online kelas dunia yang mudah, transparan, dan aman bagi setiap pecinta fashion di seluruh Nusantara. Melalui integrasi teknologi terdepan <strong>Interactive 3D Three.js</strong>, Anda dapat meneliti lekukan siluet tas, tekstur butiran kulit (*grain*), serta kilau perangkat keras logam emas 18K secara 360 derajat langsung dari layar ponsel maupun komputer Anda tanpa perlu menebak-nebak ukuran asli fisik tas.
                </p>
                <p>
                    Tidak hanya kemudahan visual, berbelanja tas di <strong>Mikael On Shop</strong> didukung sistem transaksi terenkripsi berstandar ISO 27001 serta beragam metode pembayaran fleksibel mulai dari QRIS instan, Virtual Account semua bank terkemuka di Indonesia, hingga cicilan kartu kredit 0%. Kerjasama erat dengan jaringan ekspedisi logistik terkemuka memastikan paket tas idaman Anda dikemas secara eksklusif dengan kotak *hardbox* mewah, kantong *dustbag* satin sutra, dan kartu garansi resmi bernomor seri unik yang diantar langsung ke depan pintu rumah Anda dengan asuransi pengiriman penuh.
                </p>
                <p>
                    Bagi Anda yang membutuhkan panduan gaya atau konsultasi padu-padan busana untuk pesta, ke kantor, maupun liburan, tim *Personal Fashion Advisor* kami siap melayani Anda melalui WhatsApp Official setiap hari. Temukan beragam pilihan kategori tas mulai dari <strong>Handbag & Tote Bag</strong> berkapasitas besar, <strong>Shoulder & Sling Bag</strong> yang chic nan kasual, <strong>Luxury Backpack</strong> untuk mobilitas modern, hingga <strong>Clutch Pouch</strong> yang anggun untuk melengkapi penampilan glamor Anda. Ayo mulai jelajahi koleksi tas kulit mewah terbaik dan rasakan sentuhan keanggunan abadi bersama Mikael On Shop.
                </p>
            </div>

            <!-- TOP PENCARIAN POPULER (ALA FOOTER KEYWORD DIRECTORY TOKOPEDIA) -->
            <div class="pt-6 border-t border-rosepet-soft space-y-3">
                <h4 class="font-bold text-xs uppercase tracking-wider text-rosepet-dark flex items-center gap-2">
                    <svg class="w-4 h-4 text-rosepet-fresh" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                    <span>Top Pencarian Populer Tas Kulit di Mikael On Shop:</span>
                </h4>
                <div class="flex flex-wrap items-center gap-x-2 gap-y-1.5 text-xs text-rosepet-muted">
                    <a href="#katalog" class="hover:text-rosepet-fresh hover:underline">tas wanita mewah</a>
                    <span class="text-rosepet-border font-bold">|</span>
                    <a href="#katalog" class="hover:text-rosepet-fresh hover:underline">tote bag kulit asli</a>
                    <span class="text-rosepet-border font-bold">|</span>
                    <a href="#katalog" class="hover:text-rosepet-fresh hover:underline">handbag pesta elegan</a>
                    <span class="text-rosepet-border font-bold">|</span>
                    <a href="#katalog" class="hover:text-rosepet-fresh hover:underline">shoulder bag wanita branded</a>
                    <span class="text-rosepet-border font-bold">|</span>
                    <a href="#katalog" class="hover:text-rosepet-fresh hover:underline">tas kulit italia original</a>
                    <span class="text-rosepet-border font-bold">|</span>
                    <a href="#katalog" class="hover:text-rosepet-fresh hover:underline">sling bag wanita kekinian</a>
                    <span class="text-rosepet-border font-bold">|</span>
                    <a href="#katalog" class="hover:text-rosepet-fresh hover:underline">tas kerja wanita elegan</a>
                    <span class="text-rosepet-border font-bold">|</span>
                    <a href="#katalog" class="hover:text-rosepet-fresh hover:underline">clutch pesta rose gold</a>
                    <span class="text-rosepet-border font-bold">|</span>
                    <a href="#katalog" class="hover:text-rosepet-fresh hover:underline">tas ransel kulit wanita</a>
                    <span class="text-rosepet-border font-bold">|</span>
                    <a href="#katalog" class="hover:text-rosepet-fresh hover:underline">official store tas wanita jakarta</a>
                </div>
            </div>

        </div>

    </section>

    <!-- FOOTER RESMI (PROFESSIONAL E-COMMERCE FOOTER ALA TOKOPEDIA/SHOPEE) -->
    <footer class="bg-white border-t-2 border-rosepet-border pt-16 pb-12 mt-12">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-rosepet-soft">
            <div class="space-y-4">
                <div class="flex items-center gap-3.5">
                    <img src="{{ asset('assets/logo.png') }}" alt="Mikael On Shop" class="w-12 h-12 object-contain drop-shadow-md">
                    <span class="font-serif font-black text-2xl tracking-wider uppercase text-rosepet-dark block">Mikael On Shop</span>
                </div>
                <p class="text-xs text-rosepet-muted leading-relaxed">
                    Brand tas kulit mewah karya pengrajin ahli dengan dedikasi tinggi terhadap presisi, estetika arsitektur modern, dan keanggunan abadi.
                </p>
                <div class="text-xs text-rosepet-dark font-bold">
                    📍 Showroom: Senopati, Kebayoran Baru, Jakarta Selatan
                </div>
            </div>

            <div>
                <h4 class="font-bold text-xs uppercase tracking-wider text-rosepet-dark mb-4">Layanan Pelanggan</h4>
                <ul class="space-y-2 text-xs text-rosepet-muted">
                    <li><a href="#" class="hover:text-rosepet-fresh">Panduan Ukuran Tas</a></li>
                    <li><a href="#" class="hover:text-rosepet-fresh">Perawatan Kulit Asli</a></li>
                    <li><a href="#" class="hover:text-rosepet-fresh">Kebijakan Garansi & Retur</a></li>
                    <li><a href="#" class="hover:text-rosepet-fresh">Lacak Status Pesanan</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-xs uppercase tracking-wider text-rosepet-dark mb-4">Metode Pengiriman & Mitra</h4>
                <div class="flex flex-wrap gap-2 text-[10px] font-bold text-rosepet-dark">
                    <span class="px-2.5 py-1 rounded bg-[#FFF5F7] border border-rosepet-border">JNE YES / REG</span>
                    <span class="px-2.5 py-1 rounded bg-[#FFF5F7] border border-rosepet-border">SiCepat BEST</span>
                    <span class="px-2.5 py-1 rounded bg-[#FFF5F7] border border-rosepet-border">GoSend / Grab Instant</span>
                </div>
                <h4 class="font-bold text-xs uppercase tracking-wider text-rosepet-dark mt-6 mb-3">Keamanan Transaksi</h4>
                <p class="text-[11px] text-rosepet-muted leading-relaxed">
                    Dilindungi enkripsi data SSL 256-bit & standar keamanan cyber ISO 27001.
                </p>
            </div>

            @auth
                @if(Auth::user()->role === 'admin')
                <div>
                    <h4 class="font-bold text-xs uppercase tracking-wider text-rosepet-dark mb-4">Official Admin Desk</h4>
                    <p class="text-xs text-rosepet-muted leading-relaxed mb-4">
                        Panel operasional manajemen produk tas, upload foto WebP, dan pengelolaan order.
                    </p>
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 rounded-xl bg-[#FFF5F7] hover:bg-rosepet-soft text-rosepet-deep border-2 border-rosepet-border text-xs font-bold transition-all inline-flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Masuk Admin Hub</span>
                    </a>
                </div>
                @endif
            @endauth
        </div>

        <div class="max-w-7xl mx-auto px-6 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-rosepet-muted">
            <p>© 2026 Mikael On Shop Indonesia. All Rights Reserved. Powered by Three.js & Blender MCP Engine.</p>
            <p class="text-[11px]">Bespoke Luxury Architecture • Anti-Slop Design</p>
        </div>
    </footer>

    <!-- Three.js Interactive 3D Setup Script with Feminine Lighting -->
    <script>
        let scene, camera, renderer, bagMesh, controls;
        const container = document.getElementById('three-bag-container');

        function initThree() {
            scene = new THREE.Scene();

            // Camera Setup
            camera = new THREE.PerspectiveCamera(40, container.clientWidth / container.clientHeight, 0.1, 100);
            camera.position.set(0, 0, 3.6);

            // WebGL Renderer with High-DPI & Anti-aliasing
            renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            renderer.setSize(container.clientWidth, container.clientHeight);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            renderer.toneMapping = THREE.ACESFilmicToneMapping;
            renderer.toneMappingExposure = 1.25;
            renderer.outputEncoding = THREE.sRGBEncoding;
            container.appendChild(renderer.domElement);

            // OrbitControls
            controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.enableDamping = true;
            controls.dampingFactor = 0.05;
            controls.maxPolarAngle = Math.PI / 2 + 0.1;
            controls.minDistance = 2.0;
            controls.maxDistance = 5.0;

            // Soft Ambient Light
            const ambientLight = new THREE.AmbientLight(0xfff5f7, 1.3);
            scene.add(ambientLight);

            // Key Light (Warm White)
            const keyLight = new THREE.DirectionalLight(0xfff7ee, 2.4);
            keyLight.position.set(4, 5, 4);
            scene.add(keyLight);

            // Rim Light (Rose Glow highlight on contours)
            const rimLight = new THREE.DirectionalLight(0xf8c8d4, 2.5);
            rimLight.position.set(-4, 3, -3);
            scene.add(rimLight);

            // Fill Light (Gentle reflection)
            const fillLight = new THREE.DirectionalLight(0xfce7ec, 0.9);
            fillLight.position.set(0, -2, 3);
            scene.add(fillLight);

            // Load Blender MCP Exported Model with Draco Compression
            const loader = new THREE.GLTFLoader();
            const dracoLoader = new THREE.DRACOLoader();
            dracoLoader.setDecoderPath('https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/libs/draco/');
            loader.setDRACOLoader(dracoLoader);

            loader.load('/models/lv.glb', function (gltf) {
                bagMesh = gltf.scene;
                
                // Auto compute bounding box to center geometry perfectly at (0, 0, 0)
                const box = new THREE.Box3().setFromObject(bagMesh);
                const center = box.getCenter(new THREE.Vector3());
                const size = box.getSize(new THREE.Vector3());
                
                // Center model pivot
                bagMesh.position.x = -center.x;
                bagMesh.position.y = -center.y;
                bagMesh.position.z = -center.z;
                
                // Normalize scale so it fits comfortably inside the viewport
                const maxDim = Math.max(size.x, size.y, size.z);
                const scale = 1.6 / (maxDim || 1);
                
                // Wrap in a pivot group for clean rotation around its exact center
                const pivotGroup = new THREE.Group();
                pivotGroup.add(bagMesh);
                pivotGroup.scale.set(scale, scale, scale);
                pivotGroup.position.set(0, 0, 0); // Presisi tepat di titik pusat kanvas
                scene.add(pivotGroup);
                
                // Expose pivotGroup for animation rotation
                window.bagPivotGroup = pivotGroup;

                // Hide loader
                const loaderEl = document.getElementById('canvas-loading');
                if (loaderEl) {
                    loaderEl.style.opacity = '0';
                    setTimeout(() => loaderEl.remove(), 500);
                }
            }, undefined, function (error) {
                console.error("Gagal memuat GLB Blender:", error);
            });

            // Handle Resize
            window.addEventListener('resize', onWindowResize);

            // Start Animation Loop
            animate();
        }

        function onWindowResize() {
            if (!container) return;
            camera.aspect = container.clientWidth / container.clientHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(container.clientWidth, container.clientHeight);
        }

        function animate() {
            requestAnimationFrame(animate);
            if (controls) controls.update();
            
            // Gentle floating rotation around centered pivot
            if (window.bagPivotGroup) {
                window.bagPivotGroup.rotation.y += 0.0035;
            }

            renderer.render(scene, camera);
        }

        // Live Color Customizer
        function setBagColor(hexColor, name, metalness = 0.12, roughness = 0.45) {
            if (!bagMesh) return;
            bagMesh.traverse((child) => {
                if (child.isMesh && !child.name.includes('Gold_Hardware')) {
                    child.material.color.set(hexColor);
                    child.material.roughness = roughness;
                    child.material.metalness = metalness;
                }
            });

            // Update UI buttons
            document.querySelectorAll('.color-btn').forEach(btn => {
                btn.classList.remove('border-rosepet-fresh', 'scale-110');
                btn.classList.add('border-white');
            });
            event.currentTarget.classList.remove('border-white');
            event.currentTarget.classList.add('border-rosepet-fresh', 'scale-110');
        }

        window.addEventListener('DOMContentLoaded', initThree);

        // ==========================================
        // CARD INTERACTIVE SLIDER (PHOTO & 3D WEBGL)
        // ==========================================
        const card3dInstances = {};

        function setCardPhotoSlide(productId, imgUrl, dotIndex) {
            const imgEl = document.getElementById(`slide-img-${productId}`);
            if (imgEl) {
                imgEl.style.opacity = '0.3';
                setTimeout(() => {
                    imgEl.src = imgUrl;
                    imgEl.style.opacity = '1';
                }, 150);
            }

            document.querySelectorAll(`.dot-${productId}`).forEach((dot, idx) => {
                if (idx === dotIndex) {
                    dot.className = `w-2 h-2 rounded-full transition-all bg-white scale-125 dot-${productId}`;
                } else {
                    dot.className = `w-2 h-2 rounded-full transition-all bg-white/50 hover:bg-white dot-${productId}`;
                }
            });
            switchCardSlide(productId, 'photo');
        }

        function switchCardSlide(productId, mode, glbUrl = null) {
            const photoSlide = document.getElementById(`slide-photo-${productId}`);
            const d3Slide = document.getElementById(`slide-3d-${productId}`);
            const btnPhoto = document.getElementById(`btn-card-photo-${productId}`);
            const btn3d = document.getElementById(`btn-card-3d-${productId}`);

            if (mode === 'photo') {
                if (photoSlide) photoSlide.classList.remove('hidden');
                if (d3Slide) d3Slide.classList.add('hidden');
                if (btnPhoto) btnPhoto.className = 'px-2.5 py-1 rounded-xl bg-white/95 text-rosepet-dark text-[10px] font-bold shadow-sm border border-rosepet-soft hover:bg-rosepet-fresh hover:text-white transition-all flex items-center gap-1';
                if (btn3d) btn3d.className = 'px-2.5 py-1 rounded-xl bg-white/80 text-rosepet-deep text-[10px] font-bold shadow-sm border border-rosepet-soft hover:bg-rosepet-fresh hover:text-white transition-all flex items-center gap-1';
            } else {
                if (photoSlide) photoSlide.classList.add('hidden');
                if (d3Slide) d3Slide.classList.remove('hidden');
                if (btnPhoto) btnPhoto.className = 'px-2.5 py-1 rounded-xl bg-white/80 text-rosepet-deep text-[10px] font-bold shadow-sm border border-rosepet-soft hover:bg-rosepet-fresh hover:text-white transition-all flex items-center gap-1';
                if (btn3d) btn3d.className = 'px-2.5 py-1 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white text-[10px] font-bold shadow-sm border border-transparent transition-all flex items-center gap-1';

                if (!card3dInstances[productId] && glbUrl) {
                    initCard3d(productId, glbUrl);
                } else if (card3dInstances[productId]) {
                    const inst = card3dInstances[productId];
                    setTimeout(() => {
                        const container = document.getElementById(`slide-3d-${productId}`);
                        if (container && inst.renderer && inst.camera) {
                            inst.camera.aspect = container.clientWidth / container.clientHeight;
                            inst.camera.updateProjectionMatrix();
                            inst.renderer.setSize(container.clientWidth, container.clientHeight);
                        }
                    }, 100);
                }
            }
        }

        function initCard3d(productId, glbUrl) {
            const container = document.getElementById(`slide-3d-${productId}`);
            const canvas = document.getElementById(`canvas-card-3d-${productId}`);
            const loaderEl = document.getElementById(`loader-card-3d-${productId}`);

            const width = container.clientWidth || 300;
            const height = container.clientHeight || 288;

            const scene = new THREE.Scene();
            scene.background = new THREE.Color(0xFFF7F8);

            const camera = new THREE.PerspectiveCamera(40, width / height, 0.1, 100);
            camera.position.set(0, 0.2, 1.8);

            const renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: true });
            renderer.setSize(width, height);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            renderer.toneMapping = THREE.ACESFilmicToneMapping;
            renderer.toneMappingExposure = 1.15;

            const controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.enableDamping = true;
            controls.dampingFactor = 0.05;
            controls.minDistance = 0.8;
            controls.maxDistance = 3.5;

            scene.add(new THREE.AmbientLight(0xFFFFFF, 1.2));
            const keyL = new THREE.DirectionalLight(0xFFF0F5, 1.6);
            keyL.position.set(2, 4, 3);
            scene.add(keyL);

            const fillL = new THREE.DirectionalLight(0xEBB4C4, 0.9);
            fillL.position.set(-3, 1, 2);
            scene.add(fillL);

            const pivotGroup = new THREE.Group();
            scene.add(pivotGroup);

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
                    const scale = 1.0 / maxDim;
                    pivotGroup.scale.set(scale, scale, scale);
                }

                pivotGroup.add(model);
                if (loaderEl) loaderEl.style.display = 'none';

                card3dInstances[productId] = { scene, camera, renderer, controls, pivotGroup };

                function animateCard() {
                    requestAnimationFrame(animateCard);
                    controls.update();
                    pivotGroup.rotation.y += 0.003;
                    renderer.render(scene, camera);
                }
                animateCard();
            });
        }

        // Auto-Inisialisasi Model 3D pada Card Katalog yang Memiliki 3D Path
        document.addEventListener('DOMContentLoaded', () => {
            const card3dContainers = document.querySelectorAll('[id^="slide-3d-"][data-glb-url]');
            card3dContainers.forEach(container => {
                const productId = container.id.replace('slide-3d-', '');
                const glbUrl = container.getAttribute('data-glb-url');
                if (productId && glbUrl && !card3dInstances[productId]) {
                    initCard3d(productId, glbUrl);
                }
            });
        });
    </script>
</body>
</html>
