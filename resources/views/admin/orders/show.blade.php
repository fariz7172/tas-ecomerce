@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Breadcrumb & Back -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-rosepet-fresh hover:underline inline-flex items-center gap-1.5 mb-2">
                <span>← Kembali ke Order Board</span>
            </a>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-rosepet-dark">
                Pesanan #<span class="font-mono">{{ $order->order_number }}</span>
            </h1>
            <p class="text-xs text-rosepet-muted mt-0.5">Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
        </div>

        <!-- Tombol Aksi Cetak & Sync -->
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.order.invoice', $order->id) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white text-xs font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                <span>🖨️ Cetak Invoice Toko</span>
            </a>
            <a href="{{ route('admin.order.thermal_label', $order->id) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white border border-rosepet-soft text-rosepet-dark hover:bg-rosepet-soft/50 text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                <span>🏷️ Resi Thermal 10x15</span>
            </a>
            @if($order->payment_method !== 'COD')
            <form action="{{ route('admin.order.sync_midtrans', $order->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-3.5 py-2.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-bold border border-emerald-200 transition-all flex items-center gap-1.5" title="Sinkronkan status bayar Midtrans">
                    <span>🔄 Sinkron Midtrans</span>
                </button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
        {{ session('error') }}
    </div>
    @endif

    @php
        $statusLabels = [
            'pending' => ['text' => '⏳ Menunggu Pembayaran', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
            'paid' => ['text' => '💰 Sudah Lunas', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
            'processing' => ['text' => '📦 Sedang Dipacking', 'class' => 'bg-indigo-50 text-indigo-700 border-indigo-200'],
            'shipped' => ['text' => '🚚 Sedang Dikirim', 'class' => 'bg-sky-50 text-sky-700 border-sky-200'],
            'completed' => ['text' => '✅ Selesai', 'class' => 'bg-gray-50 text-gray-700 border-gray-200'],
            'cancelled' => ['text' => '❌ Dibatalkan', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
        ];
        $badge = $statusLabels[$order->status] ?? ['text' => $order->status, 'class' => 'bg-gray-50 text-gray-700 border-gray-200'];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- Sisi Kiri: Rincian Produk & Biaya (Col 2) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Daftar Produk Pesanan -->
            <div class="bg-white rounded-3xl p-6 border border-rosepet-soft shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-rosepet-soft pb-3">
                    <h3 class="font-serif font-bold text-base text-rosepet-dark">Rincian Item Tas</h3>
                    <span class="text-xs text-rosepet-muted">{{ $order->items->sum('quantity') }} Total Pcs</span>
                </div>

                <div class="divide-y divide-rosepet-soft/50">
                    @foreach($order->items as $item)
                    @php
                        $prod = $item->variant?->product;
                        $rawImg = $prod?->images->first()?->image_path ?: ($item->variant?->image_path ?: null);
                        $imgUrl = $rawImg ? (str_starts_with($rawImg, 'http') ? $rawImg : asset($rawImg)) : 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=400&q=80';
                    @endphp
                    <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <img src="{{ $imgUrl }}" alt="{{ $item->product_name }}" class="w-14 h-14 rounded-2xl object-contain bg-[#FAF7F8] border border-rosepet-soft flex-shrink-0">
                            <div class="min-w-0">
                                <h4 class="font-bold text-rosepet-dark text-sm truncate">{{ $item->product_name }}</h4>
                                <div class="text-xs text-rosepet-muted flex items-center gap-2 mt-0.5">
                                    <span>Warna: <strong>{{ $item->variant_name }}</strong></span>
                                    <span>•</span>
                                    <span>Jumlah: <strong>{{ $item->quantity }} pcs</strong></span>
                                </div>
                                <div class="text-[11px] text-rosepet-fresh font-semibold mt-0.5">
                                    @ Rp {{ number_format($item->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Subtotal</span>
                            <span class="font-serif font-black text-sm text-rosepet-dark">
                                Rp {{ number_format($item->total_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Catatan Pembeli -->
                @if($order->notes)
                <div class="mt-4 p-3.5 rounded-2xl bg-[#FFF9FA] border border-rosepet-soft text-xs space-y-1">
                    <span class="text-[10px] font-bold uppercase text-rosepet-muted block">Catatan Tambahan Pembeli:</span>
                    <p class="text-rosepet-dark italic font-medium">"{{ $order->notes }}"</p>
                </div>
                @endif
            </div>

            <!-- Rincian Biaya & Total Transaksi -->
            <div class="bg-white rounded-3xl p-6 border border-rosepet-soft shadow-sm space-y-3 text-xs">
                <h3 class="font-serif font-bold text-base text-rosepet-dark border-b border-rosepet-soft pb-3">Ringkasan Pembayaran</h3>
                
                <div class="flex justify-between text-rosepet-muted">
                    <span>Subtotal Produk:</span>
                    <span class="font-bold text-rosepet-dark font-mono">
                        Rp {{ number_format($order->grand_total - $order->shipping_cost, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between text-rosepet-muted">
                    <span>Biaya Pengiriman ({{ $order->courier_code ?: 'Kurir' }}):</span>
                    <span class="font-bold text-emerald-700 font-mono">
                        {{ $order->shipping_cost > 0 ? 'Rp ' . number_format($order->shipping_cost, 0, ',', '.') : 'Rp 0 (Bebas Ongkir / COD)' }}
                    </span>
                </div>

                <div class="pt-3 border-t border-rosepet-soft flex justify-between items-center">
                    <div>
                        <span class="font-black text-sm uppercase text-rosepet-dark block">Total Tagihan Akhir:</span>
                        <span class="text-[10px] text-rosepet-muted">Sudah termasuk PPN & Asuransi Penuh</span>
                    </div>
                    <span class="font-serif font-black text-2xl text-rosepet-fresh">
                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

        </div>

        <!-- Sisi Kanan: Status, Alamat & Kontrol Pemenuhan Gudang (Col 1) -->
        <div class="space-y-6">

            <!-- Card Status & Pembayaran -->
            <div class="bg-white rounded-3xl p-6 border border-rosepet-soft shadow-sm space-y-4">
                <h3 class="font-serif font-bold text-base text-rosepet-dark border-b border-rosepet-soft pb-2.5">Status Transaksi</h3>

                <div class="space-y-2">
                    <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Status Pesanan Saat Ini:</span>
                    <div class="inline-block px-3 py-1 rounded-full text-xs font-bold border {{ $badge['class'] }}">
                        {{ $badge['text'] }}
                    </div>
                </div>

                <div class="space-y-1">
                    <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Metode Pembayaran:</span>
                    <span class="text-xs font-bold text-rosepet-dark font-mono bg-rosepet-soft/50 px-2.5 py-1 rounded-lg inline-block">
                        {{ $order->payment_method }}
                    </span>
                    @if($order->payment_channel)
                    <div class="text-[11px] text-rosepet-muted mt-1">
                        Channel: <strong class="text-rosepet-dark">{{ $order->payment_channel }}</strong>
                    </div>
                    @endif
                </div>

                <!-- Update Cepat Status Pesanan -->
                <div class="pt-3 border-t border-rosepet-soft space-y-2">
                    <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Perbarui Status Gudang:</span>
                    <form action="{{ route('admin.order.status', $order->id) }}" method="POST" class="space-y-2">
                        @csrf
                        <select name="status" class="w-full text-xs rounded-xl border border-rosepet-soft bg-[#FFF9FA] px-3 py-2 font-bold text-rosepet-dark outline-none focus:border-rosepet-fresh">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>⏳ Menunggu Bayar</option>
                            <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>💰 Sudah Lunas</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>📦 Dipacking</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>🚚 Sedang Dikirim</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>✅ Selesai</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                        </select>
                        <button type="submit" class="w-full py-2 px-3 rounded-xl bg-rosepet-soft hover:bg-rosepet-border/60 text-rosepet-deep font-bold text-xs transition-all">
                            Simpan Perubahan Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Card Informasi Pengiriman & Resi -->
            <div class="bg-white rounded-3xl p-6 border border-rosepet-soft shadow-sm space-y-4">
                <h3 class="font-serif font-bold text-base text-rosepet-dark border-b border-rosepet-soft pb-2.5">Pengiriman & Resi</h3>

                <div class="space-y-1">
                    <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Kurir Layanan:</span>
                    <strong class="text-xs text-rosepet-dark">{{ $order->courier_code ?: 'JNE REG' }}</strong>
                </div>

                <div class="space-y-1">
                    <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Nomor Resi Pelacakan:</span>
                    @if($order->tracking_number)
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-sky-50 text-sky-800 border border-sky-200 font-mono font-bold text-xs">
                        <span>🚚 {{ $order->tracking_number }}</span>
                    </div>
                    @else
                    <span class="text-xs text-rosepet-muted italic">Belum dikirimkan</span>
                    @endif
                </div>

                <!-- Input / Kirim Resi Pengiriman -->
                <div class="pt-3 border-t border-rosepet-soft space-y-2">
                    @if($order->payment_method !== 'COD' && $order->status === 'pending')
                    <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-[11px] font-medium leading-relaxed">
                        ⚠️ <strong>Barang belum dibayar</strong><br>
                        Tombol pengiriman terkunci sampai status bayar lunas.
                    </div>
                    @else
                    <form action="{{ route('admin.order.ship', $order->id) }}" method="POST" class="space-y-2">
                        @csrf
                        <label class="block text-[10px] uppercase font-bold text-rosepet-muted">Input No. Resi Baru / Ganti Resi:</label>
                        <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="Kosongkan untuk auto-generate resi" class="w-full text-xs font-mono rounded-xl border border-rosepet-soft bg-[#FFF9FA] px-3 py-2 outline-none focus:border-rosepet-fresh">
                        <button type="submit" class="w-full py-2 px-3 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-xs shadow-sm hover:shadow transition-all">
                            🚚 Kirim / Update Resi Paket
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Card Informasi Penerima -->
            <div class="bg-white rounded-3xl p-6 border border-rosepet-soft shadow-sm space-y-3 text-xs">
                <h3 class="font-serif font-bold text-base text-rosepet-dark border-b border-rosepet-soft pb-2.5">Data Penerima</h3>

                <div>
                    <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Nama Penerima:</span>
                    <strong class="text-rosepet-dark text-sm">{{ $order->customer_name }}</strong>
                </div>

                <div>
                    <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Kontak WhatsApp / Telp:</span>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" class="text-emerald-700 font-mono font-bold hover:underline inline-flex items-center gap-1 mt-0.5">
                        <span>📱 {{ $order->customer_phone }}</span>
                        <span class="text-[10px] bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Chat WA</span>
                    </a>
                </div>

                @if($order->customer_email)
                <div>
                    <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Email Pelanggan:</span>
                    <span class="text-rosepet-dark">{{ $order->customer_email }}</span>
                </div>
                @endif

                <div>
                    <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Alamat Tujuan:</span>
                    <p class="text-rosepet-dark leading-relaxed mt-0.5">
                        {{ $order->address_line }}<br>
                        {{ $order->district }}, {{ $order->city }}<br>
                        {{ $order->province }}
                    </p>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
