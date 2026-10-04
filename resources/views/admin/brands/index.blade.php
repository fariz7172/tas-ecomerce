@extends('layouts.admin')

@section('title', 'Kelola Merk & Brand Tas')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl font-bold text-rosepet-dark">Kelola Merk & Brand Tas</h1>
            <p class="text-xs text-rosepet-muted mt-1">Daftar merk luxury resmi seperti Louis Vuitton, Chanel, Hermès, dan Mikael On Shop.</p>
        </div>
        <span class="px-4 py-2 rounded-xl bg-white border border-rosepet-soft text-rosepet-deep font-bold text-xs shadow-sm self-start sm:self-auto">
            Total Merk: <strong>{{ $brands->count() }} Brand</strong>
        </span>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold flex items-center justify-between">
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center justify-between">
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Form Tambah Merk Baru (1 Kolom) -->
        <div class="bg-white rounded-3xl p-6 border border-rosepet-soft shadow-sm space-y-4">
            <h3 class="font-serif text-base font-bold text-rosepet-dark border-b border-rosepet-soft pb-3">
                + Tambah Merk Tas Baru
            </h3>
            
            <form action="{{ route('admin.brands.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Nama Merk / Brand</label>
                    <input type="text" name="name" required placeholder="Contoh: Prada, Gucci, Dior" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none font-bold">
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Negara Asal (Origin)</label>
                    <input type="text" name="country_origin" value="France" placeholder="Contoh: France, Italy, USA" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Deskripsi Singkat Brand</label>
                    <textarea name="description" rows="3" placeholder="Sejarah singkat atau filosofi brand..." class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-xs uppercase tracking-wider hover:shadow-md hover:shadow-rosepet-fresh/30 transition-all">
                        Simpan Merk Baru
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Merk (2 Kolom) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-rosepet-soft shadow-sm space-y-4">
            <h3 class="font-serif text-base font-bold text-rosepet-dark border-b border-rosepet-soft pb-3">
                Daftar Brand Aktif di Katalog
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-rosepet-soft text-[10px] uppercase tracking-wider text-rosepet-muted font-bold">
                            <th class="py-3 px-3">Nama Merk</th>
                            <th class="py-3 px-3">Asal</th>
                            <th class="py-3 px-3 text-center">Koleksi Tas</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-rosepet-soft/50">
                        @foreach($brands as $brand)
                        @php
                            $secureBrandId = \App\Services\SecureIdService::encrypt($brand->id);
                        @endphp
                        <tr class="hover:bg-[#FFF9FA] transition-colors">
                            <td class="py-3.5 px-3">
                                <div class="font-bold text-rosepet-dark text-sm">{{ $brand->name }}</div>
                                <div class="text-[10px] text-rosepet-muted mt-0.5 line-clamp-1">{{ $brand->description ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="px-2.5 py-1 rounded-full bg-[#FFF5F7] border border-rosepet-soft text-rosepet-deep text-[10px] font-bold">
                                    {{ $brand->country_origin ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                <span class="px-2.5 py-1 rounded-lg bg-rosepet-soft/40 text-rosepet-deep font-bold text-xs">
                                    {{ $brand->products_count }} tas
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-right">
                                <form action="{{ route('admin.brands.destroy', $secureBrandId) }}" method="POST" onsubmit="return confirm('Hapus merk {{ $brand->name }}?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl text-rose-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Merk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
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
