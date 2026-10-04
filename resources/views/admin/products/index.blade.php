@extends('layouts.admin')

@section('title', 'Katalog Produk & Varian')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl font-bold text-rosepet-dark">Katalog Produk & Varian Tas</h1>
            <p class="text-xs text-rosepet-muted">Kelola master spesifikasi tas, dimensi, varian warna, harga promo, dan stok gudang.</p>
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-xs uppercase tracking-wider shadow-md hover:shadow-lg hover:shadow-rosepet-fresh/30 transition-all inline-flex items-center gap-2">
                <span>+ Tambah Tas Baru</span>
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
        {{ session('success') }}
    </div>
    @endif

    <!-- Filter Bar: Search, Kategori & Merk -->
    <div class="bg-white rounded-3xl p-5 border border-rosepet-soft shadow-sm">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <!-- 1. Search Box -->
            <div class="sm:col-span-5 space-y-1">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-rosepet-muted">Pencarian Nama / Material</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-rosepet-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari tas, bahan kulit, siluet..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-rosepet-soft bg-[#FFF9FA] text-xs text-rosepet-dark focus:border-rosepet-fresh focus:bg-white focus:outline-none transition-all font-medium">
                </div>
            </div>

            <!-- 2. Filter Kategori -->
            <div class="sm:col-span-3 space-y-1">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-rosepet-muted">Filter Kategori</label>
                <select name="category_id" class="w-full px-3 py-2.5 rounded-xl border border-rosepet-soft bg-[#FFF9FA] text-xs text-rosepet-dark focus:border-rosepet-fresh focus:bg-white focus:outline-none transition-all font-medium">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 3. Filter Merk / Brand -->
            <div class="sm:col-span-3 space-y-1">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-rosepet-muted">Filter Merk / Brand</label>
                <select name="brand_id" class="w-full px-3 py-2.5 rounded-xl border border-rosepet-soft bg-[#FFF9FA] text-xs text-rosepet-dark focus:border-rosepet-fresh focus:bg-white focus:outline-none transition-all font-medium">
                    <option value="">Semua Brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 4. Tombol Filter & Reset -->
            <div class="sm:col-span-1 flex gap-1.5">
                <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-xs shadow-sm hover:shadow transition-all flex items-center justify-center" title="Terapkan Filter">
                    <span>🔍</span>
                </button>
                @if(request()->anyFilled(['q', 'category_id', 'brand_id']))
                <a href="{{ route('admin.products.index') }}" class="py-2.5 px-3 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 font-bold text-xs border border-rose-200 transition-all flex items-center justify-center" title="Reset Filter">
                    <span>✕</span>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-3xl border border-rosepet-soft shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-rosepet-glow text-rosepet-muted font-bold uppercase text-[10px] tracking-wider border-b border-rosepet-soft">
                    <tr>
                        <th class="px-6 py-4">Foto & Nama Tas</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Spesifikasi Fisik</th>
                        <th class="px-6 py-4">Varian & Stok</th>
                        <th class="px-6 py-4">Harga Terendah</th>
                        <th class="px-6 py-4 text-center">Aksi (Encrypted URL)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rosepet-soft/50">
                    @forelse($products as $prod)
                    @php
                        $secureToken = \App\Services\SecureIdService::encrypt($prod->id);
                        $rawImg = $prod->images->first()?->image_path ?: ($prod->variants->first()?->image_path ?: null);
                        $displayImg = $rawImg 
                            ? (str_starts_with($rawImg, 'http') ? $rawImg : asset($rawImg)) 
                            : 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=400&q=80';
                    @endphp
                    <tr class="hover:bg-rosepet-soft/20 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3.5">
                                <img src="{{ $displayImg }}" alt="{{ $prod->name }}" class="w-12 h-12 rounded-xl object-contain bg-[#FAF7F8] border border-rosepet-soft">
                                <div>
                                    <div class="font-bold text-rosepet-dark">{{ $prod->name }}</div>
                                    <div class="text-[10px] text-rosepet-muted line-clamp-1 max-w-xs">{{ $prod->material }}</div>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <span class="px-2 py-0.5 rounded bg-[#FFF5F7] border border-rosepet-soft text-[9px] font-extrabold text-rosepet-deep">
                                            🏷️ {{ $prod->brand?->name ?? 'Mikael On Shop' }}
                                        </span>
                                        @if($prod->is_featured)
                                        <span class="px-2 py-0.5 rounded bg-rosepet-soft text-rosepet-deep text-[9px] font-bold">★ 3D Hero</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full bg-white border border-rosepet-soft text-[10px] font-bold text-rosepet-muted">
                                {{ $prod->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[11px] font-medium text-rosepet-dark">📐 {{ $prod->dimensions_cm }}</div>
                            <div class="text-[10px] text-rosepet-muted mt-0.5">⚖️ {{ $prod->weight_grams }}g • 🔒 {{ $prod->closure_type }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5">
                                @foreach($prod->variants as $variant)
                                <span class="w-4 h-4 rounded-full border border-white shadow-sm" style="background-color: {{ $variant->color_hex }}" title="{{ $variant->color_name }} (Stok: {{ $variant->stock }})"></span>
                                @endforeach
                            </div>
                            <div class="text-[10px] text-rosepet-muted mt-1 font-semibold">
                                Total Stok: <strong class="text-rosepet-dark">{{ $prod->variants->sum('stock') }} pcs</strong>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-serif font-black text-sm text-rosepet-dark">
                                Rp {{ number_format($prod->variants->min('promo_price') ?? 0, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.products.show', $secureToken) }}" class="p-1.5 rounded-lg text-rosepet-deep hover:bg-rosepet-soft/50 transition-colors" title="Lihat Detail Tas">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.products.edit', $secureToken) }}" class="px-3 py-1.5 rounded-lg bg-rosepet-soft text-rosepet-deep hover:bg-rosepet-fresh hover:text-white font-bold text-[11px] transition-colors flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <span>Edit</span>
                                </a>

                                <!-- Tombol Bagikan ke Facebook (Pilihan: Share Cepat ke Profil/Story ATAU Auto-Post Page API) -->
                                <div class="flex items-center gap-1">
                                    <!-- 1. Instant Web Share Dialog (Bisa langsung ke profil pribadi Fariz tanpa token API) -->
                                    @php
                                        $prodUrl = url('/product/' . $prod->slug);
                                        $brandName = $prod->brand?->name ?? 'Mikael On Shop';
                                        $minPromo = $prod->variants->min('promo_price');
                                        $minNormal = $prod->variants->min('price');
                                        $activePrice = $minPromo ?: $minNormal;
                                        $colors = $prod->variants->pluck('color_name')->unique()->implode(', ');
                                        $stock = $prod->variants->sum('stock');
                                        
                                        $promoText = $minPromo 
                                            ? 'Promo Rp ' . number_format($minPromo, 0, ',', '.') . ' (Normal Rp ' . number_format($minNormal, 0, ',', '.') . ')' 
                                            : 'Rp ' . number_format($activePrice ?? 0, 0, ',', '.');

                                        $copyCaption = "👜 {$prod->name} ({$brandName})\n"
                                            . "💰 Harga: {$promoText}\n"
                                            . "🎨 Warna: {$colors}\n"
                                            . "📦 Total Stok: {$stock} pcs\n"
                                            . "✨ Material: {$prod->material}\n"
                                            . "📐 Dimensi: {$prod->dimensions_cm}\n"
                                            . "🔒 100% Original & Garansi COD\n\n"
                                            . "Beli Sekarang:\n{$prodUrl}";

                                        $fbShareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($prodUrl) . '&quote=' . urlencode($copyCaption);
                                    @endphp
                                    <div class="flex items-center gap-1">
                                        <!-- Tombol Share FB Asli (Membuka Dialog Facebook Resmi Tanpa Ketergantungan Script) -->
                                        <a href="{{ $fbShareUrl }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-[#1877F2] hover:bg-[#166FE5] text-white font-bold text-[11px] transition-all flex items-center gap-1.5 shadow-sm" title="Bagikan ke Facebook">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                            <span>Share FB</span>
                                        </a>

                                        <!-- Tombol Salin Data Produk Lengkap -->
                                        <button type="button" onclick="copyProductCaption(this, `{{ addslashes($copyCaption) }}`)" class="p-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition-all border border-gray-200" title="Salin Rincian Produk (Nama, Merk, Harga, Stok, Warna) untuk di-Paste di FB">
                                            <span class="btn-copy-icon text-xs">📋</span>
                                        </button>
                                    </div>

                                    <!-- 2. Auto-Post API (Khusus Facebook Page / Fanspage) -->
                                    <form action="{{ route('admin.products.post_facebook', $secureToken) }}" method="POST" onsubmit="return confirm('Publikasikan tas {{ addslashes($prod->name) }} ke Facebook Page resmi toko?')" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-lg bg-[#1877F2]/10 hover:bg-[#1877F2] text-[#1877F2] hover:text-white transition-all border border-[#1877F2]/30" title="Auto-Post via Facebook Page API (Perlu Token .env)">
                                            <span class="text-[10px] font-bold">API</span>
                                        </button>
                                    </form>
                                </div>

                                <form action="{{ route('admin.products.destroy', $secureToken) }}" method="POST" onsubmit="return confirm('Hapus tas {{ $prod->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 transition-colors" title="Hapus Produk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-rosepet-muted">
                            <div class="space-y-2">
                                <span class="text-3xl block">🔍</span>
                                <p class="font-bold text-rosepet-dark">Tidak ada produk tas yang sesuai kriteria.</p>
                                <p class="text-xs">Coba ubah kata kunci pencarian atau reset filter kategori & merk.</p>
                                <a href="{{ route('admin.products.index') }}" class="inline-block mt-2 px-4 py-1.5 rounded-xl bg-rosepet-soft text-rosepet-deep font-bold text-xs hover:bg-rosepet-border transition-colors">
                                    Tampilkan Semua Produk
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-rosepet-soft/60 bg-[#FFF9FA]">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    function copyProductCaption(buttonEl, text) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => showCopiedToast(buttonEl));
        } else {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                showCopiedToast(buttonEl);
            } catch (err) {
                console.error('Fallback copy error:', err);
            }
            document.body.removeChild(textArea);
        }
    }

    function showCopiedToast(btn) {
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<span class="text-[10px] text-emerald-600 font-bold">✓ Disalin</span>';
        btn.classList.add('bg-emerald-50', 'border-emerald-200');
        setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.classList.remove('bg-emerald-50', 'border-emerald-200');
        }, 2000);
    }
</script>
@endsection
