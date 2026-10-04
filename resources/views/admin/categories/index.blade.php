@extends('layouts.admin')

@section('title', 'Kategori Tas')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl font-bold text-rosepet-dark">Kelola Kategori Tas</h1>
            <p class="text-xs text-rosepet-muted">Atur segmentasi tas wanita & pria (Handbag, Shoulder Bag, Backpack, Clutch, dll).</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add Category Form -->
        <div class="bg-white rounded-3xl p-6 border border-rosepet-soft shadow-sm h-fit space-y-4">
            <h2 class="font-serif text-lg font-bold text-rosepet-dark">Tambah Kategori Baru</h2>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Nama Kategori</label>
                    <input type="text" name="name" required placeholder="Contoh: Crossbody & Waist Bag" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Icon Identifier</label>
                    <input type="text" name="icon" value="bag" placeholder="Contoh: bag-handle, strap, clutch" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
                </div>
                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-xs uppercase tracking-wider hover:shadow-md hover:shadow-rosepet-fresh/30 transition-all">
                    Simpan Kategori
                </button>
            </form>
        </div>

        <!-- Category List Table -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-rosepet-soft shadow-sm overflow-hidden">
            <div class="p-6 border-b border-rosepet-soft flex items-center justify-between">
                <h2 class="font-serif text-lg font-bold text-rosepet-dark">Daftar Kategori Aktif</h2>
                <span class="text-xs text-rosepet-muted font-bold">{{ $categories->count() }} Kategori</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-rosepet-glow text-rosepet-muted font-bold uppercase text-[10px] tracking-wider border-b border-rosepet-soft">
                        <tr>
                            <th class="px-6 py-4">Nama Kategori</th>
                            <th class="px-6 py-4">Slug URL</th>
                            <th class="px-6 py-4 text-center">Jumlah Tas</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-rosepet-soft/50">
                        @foreach($categories as $cat)
                        <tr class="hover:bg-rosepet-soft/20 transition-colors">
                            <td class="px-6 py-4 font-bold text-rosepet-dark flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-rosepet-soft/60 text-rosepet-fresh flex items-center justify-center text-xs">👜</span>
                                <span>{{ $cat->name }}</span>
                            </td>
                            <td class="px-6 py-4 font-mono text-rosepet-muted text-[11px]">{{ $cat->slug }}</td>
                            <td class="px-6 py-4 text-center font-bold text-rosepet-fresh">{{ $cat->products_count }} Produk</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-rose-600 hover:bg-rose-50 font-bold text-xs transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
