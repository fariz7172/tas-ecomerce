@extends('layouts.admin')

@section('title', 'Semua Pesanan Tas')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl font-bold text-rosepet-dark">Order Management Board</h1>
            <p class="text-xs text-rosepet-muted">Pantau status pembayaran QRIS/VA, cetak resi pengiriman, dan pemrosesan gudang.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 rounded-full bg-rosepet-soft text-rosepet-deep border border-rosepet-fresh/20 text-xs font-bold">
                Total Omset: Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-white border border-rosepet-soft shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-rosepet-muted">Total Pesanan</span>
            <h3 class="font-serif text-2xl font-bold text-rosepet-dark mt-1">{{ $totalOrdersCount }}</h3>
        </div>
        <div class="p-5 rounded-2xl bg-white border border-rosepet-soft shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Siap Diproses</span>
            <h3 class="font-serif text-2xl font-bold text-emerald-600 mt-1">{{ $readyOrdersCount }}</h3>
        </div>
        <div class="p-5 rounded-2xl bg-white border border-rosepet-soft shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-sky-600">Dalam Pengiriman</span>
            <h3 class="font-serif text-2xl font-bold text-sky-600 mt-1">{{ $shippedOrdersCount }}</h3>
        </div>
        <div class="p-5 rounded-2xl bg-white border border-rosepet-soft shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600">Menunggu Pembayaran</span>
            <h3 class="font-serif text-2xl font-bold text-amber-600 mt-1">{{ $pendingOrdersCount }}</h3>
        </div>
    </div>

    <!-- Filter Bar: Search, Status Pesanan & Metode Pembayaran -->
    <div class="bg-white rounded-3xl p-5 border border-rosepet-soft shadow-sm">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <!-- 1. Search Box -->
            <div class="sm:col-span-5 space-y-1">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-rosepet-muted">Pencarian No. Order / Pelanggan / Resi / Kota</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-rosepet-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari MK-2026..., nama pembeli, 0812..., kota..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-rosepet-soft bg-[#FFF9FA] text-xs text-rosepet-dark focus:border-rosepet-fresh focus:bg-white focus:outline-none transition-all font-medium">
                </div>
            </div>

            <!-- 2. Filter Status Pesanan -->
            <div class="sm:col-span-3 space-y-1">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-rosepet-muted">Filter Status</label>
                <select name="status" class="w-full px-3 py-2.5 rounded-xl border border-rosepet-soft bg-[#FFF9FA] text-xs text-rosepet-dark focus:border-rosepet-fresh focus:bg-white focus:outline-none transition-all font-medium">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Menunggu Pembayaran</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>💰 Sudah Lunas</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>📦 Sedang Dipacking</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>🚚 Sedang Dikirim</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>✅ Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                </select>
            </div>

            <!-- 3. Filter Metode Pembayaran -->
            <div class="sm:col-span-3 space-y-1">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-rosepet-muted">Metode Pembayaran</label>
                <select name="payment_method" class="w-full px-3 py-2.5 rounded-xl border border-rosepet-soft bg-[#FFF9FA] text-xs text-rosepet-dark focus:border-rosepet-fresh focus:bg-white focus:outline-none transition-all font-medium">
                    <option value="">Semua Metode</option>
                    <option value="COD" {{ request('payment_method') === 'COD' ? 'selected' : '' }}>COD (Bayar di Tempat)</option>
                    <option value="QRIS" {{ request('payment_method') === 'QRIS' ? 'selected' : '' }}>QRIS Instan</option>
                    <option value="TRANSFER" {{ request('payment_method') === 'TRANSFER' ? 'selected' : '' }}>Transfer Bank / VA</option>
                </select>
            </div>

            <!-- 4. Tombol Aksi Filter & Reset -->
            <div class="sm:col-span-1 flex gap-1.5">
                <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-xs shadow-sm hover:shadow transition-all flex items-center justify-center" title="Terapkan Filter">
                    <span>🔍</span>
                </button>
                @if(request()->anyFilled(['q', 'status', 'payment_method']))
                <a href="{{ route('admin.dashboard') }}" class="py-2.5 px-3 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 font-bold text-xs border border-rose-200 transition-all flex items-center justify-center" title="Reset Filter">
                    <span>✕</span>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="rounded-3xl bg-white border border-rosepet-soft overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-rosepet-glow text-rosepet-muted font-bold uppercase text-[10px] tracking-wider border-b border-rosepet-soft">
                    <tr>
                        <th class="px-6 py-4">No. Order / Pelanggan</th>
                        <th class="px-6 py-4">Rincian Tas</th>
                        <th class="px-6 py-4">Alamat & Kurir</th>
                        <th class="px-6 py-4">Total & Bayar</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi Gudang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rosepet-soft/50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-rosepet-soft/20 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.order.show', $order->id) }}" class="font-mono font-bold text-rosepet-dark hover:text-rosepet-fresh hover:underline inline-flex items-center gap-1 group" title="Buka Detail Lengkap Pesanan">
                                <span>{{ $order->order_number }}</span>
                                <span class="text-xs text-rosepet-fresh opacity-70 group-hover:opacity-100">↗</span>
                            </a>
                            <div class="text-[11px] text-rosepet-muted mt-0.5">{{ $order->customer_name }}</div>
                            <div class="text-[10px] text-rosepet-fresh font-mono mt-0.5">📱 {{ $order->customer_phone }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @foreach($order->items as $item)
                            <div class="font-semibold text-rosepet-dark">{{ $item->product_name }}</div>
                            <div class="text-[10px] text-rosepet-deep font-medium">Warna: {{ $item->variant_name }} (x{{ $item->quantity }})</div>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-rosepet-muted max-w-xs truncate">{{ $order->address_line }}</div>
                            <div class="text-[10px] text-rosepet-dark font-bold mt-0.5">{{ $order->district }}, {{ $order->city }}</div>
                            <div class="text-[10px] text-sky-600 mt-0.5 font-semibold">🚚 {{ $order->courier_code }} - {{ $order->courier_service }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-serif font-black text-sm text-rosepet-dark">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                            <div class="text-[10px] text-rosepet-muted mt-0.5">{{ $order->payment_method }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusBadges = [
                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'processing' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'shipped' => 'bg-sky-50 text-sky-700 border-sky-200',
                                    'completed' => 'bg-gray-50 text-gray-700 border-gray-200',
                                ];
                            @endphp
                            <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusBadges[$order->status] ?? 'bg-white text-rosepet-dark' }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center gap-1.5 min-w-[150px]">
                                <!-- Tombol Aksi Detail & Cetak Invoice Resmi -->
                                <div class="grid grid-cols-2 gap-1 w-full">
                                    <a href="{{ route('admin.order.show', $order->id) }}" class="py-1 px-2 rounded-lg bg-rosepet-soft hover:bg-rosepet-border/70 text-rosepet-dark font-bold text-[10px] transition-all flex items-center justify-center gap-1 border border-rosepet-soft">
                                        <span>👁️ Detail</span>
                                    </a>
                                    <a href="{{ route('admin.order.invoice', $order->id) }}" target="_blank" class="py-1 px-2 rounded-lg bg-gray-900 hover:bg-black text-white font-bold text-[10px] transition-all flex items-center justify-center gap-1 shadow-sm">
                                        <span>🧾 Invoice</span>
                                    </a>
                                </div>

                                <!-- Tombol Cetak Label Thermal 10x15 cm -->
                                <a href="{{ route('admin.order.thermal_label', $order->id) }}" target="_blank" class="w-full py-1 px-2.5 rounded-lg bg-white text-rosepet-deep hover:bg-rosepet-soft/60 text-[10px] font-bold transition-all flex items-center justify-center gap-1 border border-rosepet-fresh/20">
                                    <span>🏷️ Resi Thermal 10x15</span>
                                </a>

                                <!-- Tombol Sinkronisasi Pembayaran Midtrans (Khusus Non-COD) -->
                                @if($order->payment_method !== 'COD' && $order->status !== 'paid' && $order->status !== 'completed')
                                <form action="{{ route('admin.order.sync_midtrans', $order->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full py-1 px-2 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white font-bold text-[10px] transition-colors border border-emerald-200 flex items-center justify-center gap-1 shadow-sm">
                                        <span>🔄 Cek Status Midtrans</span>
                                    </button>
                                </form>
                                @endif

                                <!-- Aksi Status Cepat / Input Resi Ekspedisi -->
                                @php
                                    $canShip = ($order->payment_method === 'COD') || ($order->status !== 'pending');
                                @endphp

                                @if($canShip && ($order->status === 'pending' || $order->status === 'processing' || $order->status === 'paid'))
                                <form action="{{ route('admin.order.ship', $order->id) }}" method="POST" class="w-full flex items-center gap-1">
                                    @csrf
                                    <input type="text" name="tracking_number" placeholder="No. Resi (Auto/Ketik)" class="w-full bg-[#FAF7F8] border border-rosepet-soft rounded-lg px-2 py-1 text-[10px] outline-none focus:border-rosepet-fresh font-mono">
                                    <button type="submit" title="Tandai Dikirim & Buat Resi" class="px-2 py-1 rounded-lg bg-sky-600 hover:bg-sky-700 text-white font-bold text-[10px] whitespace-nowrap shadow-sm">
                                        🚚 Kirim
                                    </button>
                                </form>
                                @elseif(!$canShip)
                                <div class="w-full py-1.5 px-2 rounded-lg bg-rose-50 border border-rose-200 text-center">
                                    <span class="text-[10px] font-bold text-rose-600 block leading-tight">⚠️ Barang belum dibayar</span>
                                    <span class="text-[8px] text-rose-400 block mt-0.5">Tunggu status lunas Midtrans</span>
                                </div>
                                @endif

                                <!-- Dropdown Ubah Status Pesanan Cepat -->
                                <form action="{{ route('admin.order.status', $order->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="w-full bg-white border border-rosepet-soft rounded-lg px-2 py-1 text-[10px] text-rosepet-dark font-semibold outline-none focus:border-rosepet-fresh cursor-pointer">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>⏳ Menunggu Bayar</option>
                                        <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>💰 Sudah Lunas</option>
                                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>📦 Packing Gudang</option>
                                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>🚚 Sedang Dikirim</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>✅ Pesanan Selesai</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                                    </select>
                                </form>

                                @if($order->tracking_number)
                                <div class="text-[9px] font-mono text-sky-700 bg-sky-50 px-2 py-0.5 rounded border border-sky-200 w-full text-center truncate">
                                    Resi: {{ $order->tracking_number }}
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-rosepet-muted">
                            <div class="space-y-2">
                                <span class="text-3xl block">📋</span>
                                <p class="font-bold text-rosepet-dark">Tidak ada data pesanan yang sesuai pencarian/filter.</p>
                                <p class="text-xs">Coba gunakan nomor order lain atau reset filter status pesanan.</p>
                                <a href="{{ route('admin.dashboard') }}" class="inline-block mt-2 px-4 py-1.5 rounded-xl bg-rosepet-soft text-rosepet-deep font-bold text-xs hover:bg-rosepet-border transition-colors">
                                    Tampilkan Semua Pesanan
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-rosepet-soft/60 bg-[#FFF9FA]">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
