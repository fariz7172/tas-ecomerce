<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout Pesanan — Mikael On Shop</title>
    
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
    <!-- Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY', 'Mid-client-rkt_8RrQGvYiucnK') }}"></script>
</head>
<body class="bg-rosepet-light text-rosepet-dark font-sans antialiased min-h-screen flex flex-col justify-between">

    <!-- Header Navbar -->
    <header class="bg-white/95 backdrop-blur-md border-b border-rosepet-border sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 sm:py-0 sm:h-20 flex flex-wrap items-center justify-between gap-3">
            <a href="/" class="flex items-center gap-3 sm:gap-3.5 flex-shrink-0 group">
                <img src="{{ asset('assets/logo.png') }}" alt="Mikael on Shop" class="w-12 h-12 sm:w-14 sm:h-14 object-contain group-hover:scale-105 transition-transform drop-shadow-md">
                <div>
                    <span class="font-serif font-black text-lg sm:text-2xl tracking-[0.12em] sm:tracking-[0.16em] uppercase text-rosepet-dark block leading-none">Mikael <span class="font-normal text-rosepet-fresh italic">on Shop</span></span>
                    <span class="text-[8px] sm:text-[9px] uppercase tracking-widest text-rosepet-muted font-bold block mt-0.5 sm:mt-1">Order Checkout</span>
                </div>
            </a>
            
            <div class="flex items-center gap-2.5 sm:gap-4 flex-wrap">
                <a href="{{ route('product.detail', $product->slug) }}" class="text-xs font-bold text-rosepet-dark hover:text-rosepet-fresh transition-colors">← Kembali ke Produk</a>
                <span class="text-xs text-rosepet-muted hidden sm:inline">Halo, <strong>{{ Auth::user()->name }}</strong></span>
            </div>
        </div>
    </header>

    <!-- Main Content Checkout -->
    <main class="max-w-5xl mx-auto px-6 py-12 flex-1 w-full">
        <div class="mb-8">
            <span class="text-xs font-black uppercase tracking-widest text-rosepet-fresh">Secure Payment Gate</span>
            <h1 class="font-serif text-3xl font-bold text-rosepet-dark mt-1">Selesaikan Pesanan Anda</h1>
            <p class="text-xs text-rosepet-muted mt-1">Lengkapi alamat pengiriman dan pilih metode pembayaran favorit Anda.</p>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" id="direct-checkout-form" onsubmit="handleDirectCheckout(event)" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            @csrf
            
            <!-- Kolom Kiri: Form Pengiriman & Pembayaran (Col 7) -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- 1. Varian & Kuantitas -->
                <div class="p-6 rounded-3xl bg-white border-2 border-rosepet-border shadow-sm space-y-4">
                    <h3 class="font-serif font-bold text-base text-rosepet-dark pb-2 border-b border-rosepet-soft">
                        1. Pilihan Varian & Kuantitas
                    </h3>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-rosepet-dark mb-2">Varian Warna:</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                            @foreach($product->variants as $index => $variant)
                            @php $isOutOfStock = $variant->stock <= 0; @endphp
                            <label class="{{ $isOutOfStock ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }}">
                                <input type="radio" name="variant_id" value="{{ $variant->id }}" data-stock="{{ $variant->stock }}" {{ ($selectedVariantId == $variant->id || ($index === 0 && !$selectedVariantId)) ? 'checked' : '' }} onchange="updateSelectedVariantPrice({{ $variant->price }}, {{ $variant->promo_price ?: $variant->price }}, {{ $variant->stock }})" class="peer sr-only">
                                <div class="p-3 rounded-2xl border-2 border-rosepet-soft bg-[#FFF9FA] peer-checked:border-rosepet-fresh peer-checked:bg-white peer-checked:shadow-sm transition-all flex items-center gap-2.5 relative">
                                    @if($isOutOfStock)
                                    <span class="absolute top-1.5 right-1.5 bg-rose-600 text-white text-[8px] font-black px-1.5 py-0.2 rounded-full uppercase">Habis</span>
                                    @endif
                                    <span class="w-4 h-4 rounded-full border border-black/10 shadow-sm flex-shrink-0" style="background-color: {{ $variant->color_hex }}"></span>
                                    <div>
                                        <div class="font-bold text-xs text-rosepet-dark leading-tight">{{ $variant->color_name }}</div>
                                        <div class="text-[10px] {{ $isOutOfStock ? 'text-rose-600 font-bold' : 'text-rosepet-muted' }}">
                                            Stok: {{ $variant->stock }}
                                        </div>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-dark">Jumlah (Pcs)</label>
                                <span id="stock-warning-badge" class="text-[10px] font-bold text-rosepet-fresh hidden">Maksimal stok tercapai</span>
                            </div>
                            <input type="number" name="quantity" id="checkout-qty" min="1" max="{{ $currentVariant->stock ?? 10 }}" value="{{ $selectedQty }}" oninput="validateQuantityInput()" onchange="validateQuantityInput()" class="w-full bg-[#FFF9FA] rounded-xl px-3.5 py-2.5 text-xs text-rosepet-dark border-2 border-rosepet-soft outline-none focus:border-rosepet-fresh font-bold">
                            <div id="stock-info-text" class="text-[10px] text-rosepet-muted mt-1">Tersedia {{ $currentVariant->stock ?? 0 }} pcs</div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-dark mb-1">Opsi Pengiriman</label>
                            <select name="courier" id="checkout-courier" onchange="calculateGrandTotal()" class="w-full bg-[#FFF9FA] rounded-xl px-3 py-2.5 text-xs text-rosepet-dark border-2 border-rosepet-soft outline-none focus:border-rosepet-fresh font-semibold cursor-pointer">
                                <option value="COD-EXPRESS" data-cost="0" selected>🚚 COD Express (Gratis Ongkir)</option>
                                <option value="JNE-REG" data-cost="15000">JNE Reguler (Rp 15.000)</option>
                                <option value="JNE-YES" data-cost="25000">JNE YES Kilat (Rp 25.000)</option>
                                <option value="SICEPAT-REG" data-cost="14000">SiCepat REG (Rp 14.000)</option>
                            </select>
                            <div id="courier-note-text" class="text-[10px] text-emerald-600 font-bold mt-1">✓ Promo COD Toko: Ongkir Rp 0</div>
                        </div>
                    </div>
                </div>

                <!-- 2. Alamat Pengiriman -->
                <div class="p-6 rounded-3xl bg-white border-2 border-rosepet-border shadow-sm space-y-4">
                    <h3 class="font-serif font-bold text-base text-rosepet-dark pb-2 border-b border-rosepet-soft">
                        2. Alamat Lengkap Pengiriman
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-rosepet-muted mb-1">Nama Penerima</label>
                            <input type="text" name="customer_name" required value="{{ Auth::user()->name }}" class="w-full bg-[#FFF9FA] rounded-xl px-3.5 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft outline-none focus:border-rosepet-fresh font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-rosepet-muted mb-1">No. WhatsApp Aktif</label>
                            <input type="text" name="customer_phone" required placeholder="08123456789" class="w-full bg-[#FFF9FA] rounded-xl px-3.5 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft outline-none focus:border-rosepet-fresh font-semibold">
                        </div>
                    </div>

                    <!-- Auto-complete Lokasi Indonesia -->
                    <div class="relative">
                        <label class="block text-[10px] font-bold uppercase text-rosepet-muted mb-1">
                            Cari Kelurahan / Kecamatan / Kota:
                        </label>
                        <div class="relative">
                            <input type="text" id="location-search-input" placeholder="Ketik nama kecamatan/kota... (Contoh: Kebayoran Baru / Bandung)" autocomplete="off" oninput="debounceSearchLocation(this.value)" class="w-full bg-white rounded-xl px-3.5 py-2.5 pl-9 text-xs text-rosepet-dark border-2 border-rosepet-soft outline-none focus:border-rosepet-fresh">
                            <span class="absolute left-3 top-3 text-xs text-rosepet-muted">🔍</span>
                            <span id="location-spinner" class="absolute right-3 top-3 text-xs text-rosepet-fresh font-bold hidden">Mencari...</span>
                        </div>
                        <div id="location-results-dropdown" class="absolute left-0 right-0 top-full mt-1 bg-white rounded-xl shadow-xl border border-rosepet-soft max-h-48 overflow-y-auto z-50 hidden divide-y divide-rosepet-soft/40"></div>
                    </div>

                    <input type="hidden" name="destination_id" id="input-destination-id" value="17536">

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-rosepet-muted mb-0.5">Provinsi</label>
                            <input type="text" name="province" id="input-province" required value="DKI Jakarta" readonly class="w-full bg-[#FAF7F8] rounded-xl px-2.5 py-1.5 text-xs text-rosepet-dark font-medium border border-rosepet-soft outline-none cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-rosepet-muted mb-0.5">Kota / Kab</label>
                            <input type="text" name="city" id="input-city" required value="Jakarta Selatan" readonly class="w-full bg-[#FAF7F8] rounded-xl px-2.5 py-1.5 text-xs text-rosepet-dark font-medium border border-rosepet-soft outline-none cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-rosepet-muted mb-0.5">Kecamatan</label>
                            <input type="text" name="district" id="input-district" required value="Kebayoran Baru" readonly class="w-full bg-[#FAF7F8] rounded-xl px-2.5 py-1.5 text-xs text-rosepet-dark font-medium border border-rosepet-soft outline-none cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-rosepet-muted mb-0.5">Kelurahan / Kodepos</label>
                            <input type="text" name="subdistrict" id="input-subdistrict" value="Cipete Utara (12150)" readonly class="w-full bg-[#FAF7F8] rounded-xl px-2.5 py-1.5 text-xs text-rosepet-dark font-medium border border-rosepet-soft outline-none cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-rosepet-muted mb-1">Alamat Lengkap & Patokan Rumah</label>
                        <textarea name="address_line" rows="2" required placeholder="Jl. Melati No. 12 RT 04 RW 02 (Pagar Hitam, Depan Taman)" class="w-full bg-[#FFF9FA] rounded-xl px-3.5 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft outline-none focus:border-rosepet-fresh"></textarea>
                    </div>
                </div>

                <!-- 3. Metode Pembayaran -->
                <div class="p-6 rounded-3xl bg-white border-2 border-rosepet-border shadow-sm space-y-4">
                    <h3 class="font-serif font-bold text-base text-rosepet-dark pb-2 border-b border-rosepet-soft">
                        3. Pilih Cara Pembayaran
                    </h3>

                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="COD" checked onchange="calculateGrandTotal()" class="peer sr-only">
                            <div class="p-3.5 rounded-2xl border-2 border-rosepet-soft peer-checked:border-emerald-500 peer-checked:bg-emerald-50 text-center transition-all relative">
                                <span class="absolute -top-2 right-2 bg-emerald-600 text-white text-[8px] font-black px-1.5 py-0.2 rounded-full uppercase">Bebas Ongkir</span>
                                <span class="text-xl block">💵</span>
                                <span class="text-xs font-bold text-rosepet-dark block mt-1">COD</span>
                                <span class="text-[9px] text-rosepet-muted block">Bayar di Tempat</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="TRANSFER" onchange="calculateGrandTotal()" class="peer sr-only">
                            <div class="p-3.5 rounded-2xl border-2 border-rosepet-soft peer-checked:border-rosepet-fresh peer-checked:bg-rosepet-soft/40 text-center transition-all">
                                <span class="text-xl block">🏦</span>
                                <span class="text-xs font-bold text-rosepet-dark block mt-1">Transfer Bank</span>
                                <span class="text-[9px] text-rosepet-muted block">BCA / Mandiri / VA</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="QRIS" onchange="calculateGrandTotal()" class="peer sr-only">
                            <div class="p-3.5 rounded-2xl border-2 border-rosepet-soft peer-checked:border-rosepet-fresh peer-checked:bg-rosepet-soft/40 text-center transition-all">
                                <span class="text-xl block">📱</span>
                                <span class="text-xs font-bold text-rosepet-dark block mt-1">QRIS</span>
                                <span class="text-[9px] text-rosepet-muted block">Gopay / OVO / Shopee</span>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Kolom Kanan: Ringkasan Produk & Pembayaran (Col 5) -->
            <div class="lg:col-span-5 sticky top-28 space-y-6">
                <div class="p-6 rounded-3xl bg-white border-2 border-rosepet-border shadow-sm space-y-4">
                    <h3 class="font-serif font-bold text-base text-rosepet-dark pb-2 border-b border-rosepet-soft">
                        Ringkasan Pesanan
                    </h3>

                    <!-- Thumbnail & Nama Produk -->
                    <div class="flex gap-4 items-center">
                        @php
                            $img = $product->images->first()?->image_path ? asset($product->images->first()->image_path) : ($currentVariant?->image_path ?? 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=400&q=80');
                        @endphp
                        <div class="w-16 h-16 rounded-2xl bg-[#FAF7F8] p-1.5 flex items-center justify-center border border-rosepet-soft flex-shrink-0">
                            <img src="{{ $img }}" alt="{{ $product->name }}" class="max-h-full max-w-full object-contain">
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-rosepet-muted uppercase">{{ $product->category->name }}</span>
                            <h4 class="font-serif font-bold text-sm text-rosepet-dark leading-snug">{{ $product->name }}</h4>
                            <div class="text-xs font-serif font-bold text-rosepet-fresh mt-0.5">Rp {{ number_format($promoPrice, 0, ',', '.') }} / pcs</div>
                        </div>
                    </div>

                    <!-- Breakdown Biaya -->
                    <div class="space-y-2 pt-3 border-t border-rosepet-soft text-xs">
                        <div class="flex justify-between text-rosepet-muted">
                            <span>Subtotal Produk:</span>
                            <span id="display-subtotal" class="font-bold text-rosepet-dark">Rp {{ number_format($promoPrice * $selectedQty, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-rosepet-muted">
                            <span>Biaya Pengiriman:</span>
                            <span id="display-shipping-cost" class="font-bold text-emerald-600">Rp 0 (COD Gratis)</span>
                        </div>
                        <div class="pt-2 border-t border-rosepet-soft flex justify-between items-baseline">
                            <span class="font-bold text-xs uppercase tracking-wider text-rosepet-dark">Total Tagihan:</span>
                            <span id="display-grand-total" class="font-serif font-black text-2xl text-rosepet-fresh">Rp {{ number_format($promoPrice * $selectedQty, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" id="btn-submit-order" class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant hover:shadow-lg hover:shadow-rosepet-fresh/30 text-white font-bold text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="btn-submit-text">Bayar Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>

                    <div class="text-[10px] text-center text-rosepet-muted flex items-center justify-center gap-1.5 pt-1">
                        <span>🔒</span>
                        <span>Transaksi Terenkripsi 256-bit SSL & Garansi 100% Genuine Leather</span>
                    </div>
                </div>
            </div>

        </form>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-rosepet-border/60 py-6 text-center text-xs text-rosepet-muted">
        <p>© 2026 Mikael On Shop. All Rights Reserved.</p>
    </footer>

    <!-- Script Kalkulasi & Midtrans Snap -->
    <script>
        let currentUnitPrice = {{ $promoPrice }};
        let currentMaxStock = {{ $currentVariant->stock ?? 10 }};

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
                submitText.innerText = 'Bayar Sekarang';
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
            const displaySubtotal = document.getElementById('display-subtotal');
            const displayShipping = document.getElementById('display-shipping-cost');
            const courierNoteEl = document.getElementById('courier-note-text');

            const paymentMethodInput = document.querySelector('input[name="payment_method"]:checked');
            const paymentMethod = paymentMethodInput ? paymentMethodInput.value : 'COD';

            const qty = parseInt(qtyInput.value) || 0;
            const subtotal = currentUnitPrice * qty;
            displaySubtotal.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');

            const selectedOption = courierSelect.options[courierSelect.selectedIndex];
            let baseShippingCost = parseInt(selectedOption.getAttribute('data-cost')) || 0;
            if (selectedOption.value !== 'COD-EXPRESS' && baseShippingCost === 0) {
                baseShippingCost = 15000;
            }

            let actualShippingCost = 0;
            if (paymentMethod === 'COD') {
                actualShippingCost = 0;
                displayShipping.innerText = 'Rp 0 (COD Gratis)';
                displayShipping.className = 'font-bold text-emerald-600';
                courierNoteEl.innerText = '✓ Promo COD Toko: Ongkir Rp 0';
                courierNoteEl.className = 'text-[10px] text-emerald-600 font-bold mt-1';
            } else {
                actualShippingCost = baseShippingCost > 0 ? baseShippingCost : 15000;
                displayShipping.innerText = 'Rp ' + actualShippingCost.toLocaleString('id-ID');
                displayShipping.className = 'font-bold text-rosepet-dark';
                courierNoteEl.innerText = 'Ongkir ekspedisi reguler';
                courierNoteEl.className = 'text-[10px] text-rosepet-muted font-medium mt-1';
            }

            const grandTotal = subtotal + actualShippingCost;
            displayEl.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        // --- Fitur Pencarian Destinasi Wilayah Indonesia ---
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
                            dropdown.innerHTML = '<div class="p-3 text-xs text-rosepet-muted text-center">Wilayah tidak ditemukan. Coba ketik nama lainnya.</div>';
                            dropdown.classList.remove('hidden');
                            return;
                        }

                        let html = '';
                        data.forEach(item => {
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
        }

        // --- Handler Checkout & Pop-up Snap Midtrans ---
        function handleDirectCheckout(e) {
            const form = document.getElementById('direct-checkout-form');
            const paymentMethodInput = document.querySelector('input[name="payment_method"]:checked');
            const paymentMethod = paymentMethodInput ? paymentMethodInput.value : 'COD';

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
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                window.location.href = data.success_url;
                            },
                            onPending: function(result) {
                                window.location.href = data.success_url;
                            },
                            onError: function(result) {
                                alert('Pembayaran gagal atau dibatalkan.');
                                window.location.href = data.success_url;
                            },
                            onClose: function() {
                                alert('Anda menutup jendela pembayaran.');
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
                    alert('Gagal menghubungi server pembayaran.');
                });
            }
        }
    </script>
</body>
</html>
