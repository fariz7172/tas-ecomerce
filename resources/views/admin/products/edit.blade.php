@extends('layouts.admin')

@section('title', 'Edit Tas & Varian')

@section('content')
@php
    $secureProductId = \App\Services\SecureIdService::encrypt($product->id);
@endphp
<div class="max-w-5xl mx-auto space-y-8">
    
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-rosepet-fresh font-bold hover:underline">← Kembali ke Katalog Tas</a>
            <div class="flex items-center gap-2 mt-1">
                <h1 class="font-serif text-3xl font-bold text-rosepet-dark">Edit Spesifikasi & Varian Warna</h1>
                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-mono font-bold flex items-center gap-1">
                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>ID Encrypted</span>
                </span>
            </div>
            <p class="text-xs text-rosepet-muted mt-0.5">Produk: <strong class="text-rosepet-dark">{{ $product->name }}</strong></p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
        {{ session('success') }}
    </div>
    @endif

    <!-- Form Edit Master Tas -->
    <form action="{{ route('admin.products.update', $secureProductId) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-8 border border-rosepet-soft shadow-sm space-y-6">
        @csrf
        @method('PUT')

        <div class="border-b border-rosepet-soft pb-4">
            <h2 class="font-serif text-lg font-bold text-rosepet-dark">1. Master Data Tas</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Nama Tas</label>
                <input type="text" name="name" value="{{ $product->name }}" required class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none font-bold">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Merk / Brand Tas</label>
                <select name="brand_id" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none font-bold">
                    <option value="">-- Tanpa Merk / Brand Lain --</option>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }} ({{ $brand->country_origin }})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Kategori Jenis Tas</label>
                <select name="category_id" required class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5 flex items-center justify-between">
                <span>Deskripsi & Ulasan Lengkap Produk</span>
                <span class="text-[10px] font-bold text-rosepet-fresh bg-rosepet-soft/50 px-2 py-0.5 rounded">✨ Rich Text Editor (CKEditor 5)</span>
            </label>
            <textarea name="description" id="editor" rows="4" placeholder="Ceritakan siluet tas, inspirasi desain, dan keanggunan jahitan tangan...">{{ $product->description }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Material</label>
                <input type="text" name="material" value="{{ $product->material }}" required class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Dimensi (P x L x T)</label>
                <input type="text" name="dimensions_cm" value="{{ $product->dimensions_cm }}" required class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Berat (Gram)</label>
                <input type="number" name="weight_grams" value="{{ $product->weight_grams }}" required class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Panjang Strap</label>
                <input type="text" name="strap_length" value="{{ $product->strap_length }}" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Tipe Penutup</label>
                <input type="text" name="closure_type" value="{{ $product->closure_type }}" required class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Kapasitas (Liter)</label>
                <input type="number" step="0.1" name="capacity_liter" value="{{ $product->capacity_liter }}" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
            </div>
        </div>

        <!-- Status Publikasi & Hero Bag -->
        <div class="p-4 rounded-2xl bg-[#FFF9FA] border border-rosepet-soft/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="w-4 h-4 rounded text-rosepet-fresh focus:ring-rosepet-fresh border-rosepet-border cursor-pointer">
                <div>
                    <label for="is_active" class="text-xs font-bold text-rosepet-dark cursor-pointer">Tampilkan Produk di Toko (Aktif di Katalog Publik)</label>
                    <p class="text-[10px] text-rosepet-muted">Jika dicentang, tas akan muncul di halaman beranda dan katalog pembeli.</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="w-4 h-4 rounded text-rosepet-fresh focus:ring-rosepet-fresh border-rosepet-border cursor-pointer">
                <div>
                    <label for="is_featured" class="text-xs font-bold text-rosepet-dark cursor-pointer">Jadikan 3D Hero Bag</label>
                    <p class="text-[10px] text-rosepet-muted">Tampilkan tas ini di panggung hero atas.</p>
                </div>
            </div>
        </div>

        <!-- Tambah Foto WebP & Model 3D Tambahan -->
        <div class="pt-4 border-t border-rosepet-soft space-y-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-deep mb-1">Ganti / Upload Model 3D Tas (.glb)</label>
                <input type="file" name="model_3d" accept=".glb" class="w-full text-xs text-rosepet-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rosepet-soft file:text-rosepet-deep hover:file:bg-rosepet-fresh hover:file:text-white cursor-pointer">
                @if($product->model_3d_path)
                <p class="text-[10px] text-emerald-600 font-semibold mt-1">✓ File 3D aktif: <code class="bg-gray-100 px-1 py-0.5 rounded">{{ $product->model_3d_path }}</code></p>
                @endif
            </div>

            <div class="pt-2">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-deep mb-1">Tambah Foto WebP Tambahan (Opsional)</label>
                <input type="file" name="photos[]" multiple accept="image/*" class="w-full text-xs text-rosepet-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rosepet-soft file:text-rosepet-deep hover:file:bg-rosepet-fresh hover:file:text-white">
            </div>
        </div>

        <div class="pt-2 flex items-center justify-end">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-xs uppercase tracking-wider hover:shadow-md hover:shadow-rosepet-fresh/30 transition-all">
                Update Spesifikasi Tas
            </button>
        </div>
    </form>

    <!-- Manajemen Varian Warna & Stok -->
    <div class="bg-white rounded-3xl p-8 border border-rosepet-soft shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-rosepet-soft pb-4">
            <div>
                <h2 class="font-serif text-lg font-bold text-rosepet-dark">2. Kelola Varian Warna & Stok Gudang</h2>
                <p class="text-xs text-rosepet-muted">Setiap tas bisa memiliki banyak pilihan warna dengan SKU, harga promo, dan stok tersendiri.</p>
            </div>
        </div>

        <!-- Existing Variants List -->
        <div class="space-y-3">
            @foreach($product->variants as $variant)
            @php
                $secureVariantId = \App\Services\SecureIdService::encrypt($variant->id);
            @endphp
            <div class="p-4 rounded-2xl bg-[#FFF9FA] border border-rosepet-soft space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full border-2 border-white shadow-sm flex-shrink-0" style="background-color: {{ $variant->color_hex }}"></span>
                        <div>
                            <div class="font-bold text-xs text-rosepet-dark flex items-center gap-2">
                                <span>{{ $variant->color_name }}</span>
                                <span class="font-mono text-[10px] text-rosepet-muted bg-white px-2 py-0.5 rounded border border-rosepet-soft">{{ $variant->sku }}</span>
                            </div>
                            <div class="text-[11px] text-rosepet-muted mt-0.5">
                                Normal: Rp {{ number_format($variant->price, 0, ',', '.') }} • 
                                <strong class="text-rosepet-fresh">Promo: Rp {{ number_format($variant->promo_price, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold px-3 py-1 rounded-lg bg-white border border-rosepet-soft text-rosepet-dark">
                            Stok: <strong>{{ $variant->stock }} pcs</strong>
                        </span>

                        <!-- Tombol Buka Form Edit Varian -->
                        <button type="button" onclick="toggleEditVariant('{{ $variant->id }}')" class="px-3 py-1.5 rounded-lg bg-white border border-rosepet-soft text-rosepet-deep hover:bg-rosepet-soft font-bold text-[11px] transition-colors flex items-center gap-1 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Edit</span>
                        </button>

                        <form action="{{ route('admin.variants.destroy', $secureVariantId) }}" method="POST" onsubmit="return confirm('Hapus varian warna ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 transition-colors" title="Hapus Varian">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Form Edit Varian (Inline Collapsible) -->
                <form id="edit-variant-form-{{ $variant->id }}" action="{{ route('admin.variants.update', $secureVariantId) }}" method="POST" class="hidden pt-3 border-t border-rosepet-soft/70 space-y-3">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-2.5">
                        <div>
                            <label class="block text-[9px] font-bold uppercase text-rosepet-muted mb-0.5">Nama Warna</label>
                            <input type="text" name="color_name" value="{{ $variant->color_name }}" required class="w-full bg-white rounded-lg px-2.5 py-1.5 text-xs text-rosepet-dark border border-rosepet-soft outline-none focus:border-rosepet-fresh">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold uppercase text-rosepet-muted mb-0.5">Hex Warna</label>
                            <div class="flex items-center gap-1.5">
                                <input type="color" value="{{ $variant->color_hex }}" onchange="document.getElementById('hex-input-{{ $variant->id }}').value = this.value" class="w-8 h-8 rounded-lg cursor-pointer border border-rosepet-soft p-0.5 bg-white">
                                <input type="text" id="hex-input-{{ $variant->id }}" name="color_hex" value="{{ $variant->color_hex }}" required class="w-full bg-white rounded-lg px-2 py-1.5 text-xs text-rosepet-dark border border-rosepet-soft outline-none font-mono">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold uppercase text-rosepet-muted mb-0.5">Harga Normal</label>
                            <input type="number" name="price" value="{{ (int)$variant->price }}" required class="w-full bg-white rounded-lg px-2.5 py-1.5 text-xs text-rosepet-dark border border-rosepet-soft outline-none focus:border-rosepet-fresh">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold uppercase text-rosepet-muted mb-0.5">Harga Promo</label>
                            <input type="number" name="promo_price" value="{{ (int)$variant->promo_price }}" class="w-full bg-white rounded-lg px-2.5 py-1.5 text-xs text-rosepet-dark border border-rosepet-soft outline-none focus:border-rosepet-fresh">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold uppercase text-rosepet-muted mb-0.5">Stok</label>
                            <input type="number" name="stock" value="{{ $variant->stock }}" required class="w-full bg-white rounded-lg px-2.5 py-1.5 text-xs text-rosepet-dark border border-rosepet-soft outline-none focus:border-rosepet-fresh">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <button type="button" onclick="toggleEditVariant('{{ $variant->id }}')" class="px-3 py-1 rounded-lg text-rosepet-muted text-xs hover:bg-gray-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-1.5 rounded-lg bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-xs uppercase tracking-wider shadow-sm hover:shadow transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
            @endforeach
        </div>

        <!-- Form Tambah Varian Baru -->
        <form action="{{ route('admin.variants.store', $secureProductId) }}" method="POST" class="pt-6 border-t border-rosepet-soft space-y-4">
            @csrf
            <h3 class="font-serif text-sm font-bold text-rosepet-dark">+ Tambah Varian Warna Baru</h3>
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-rosepet-muted mb-1">Nama Warna</label>
                    <input type="text" name="color_name" required placeholder="Contoh: Powder Blue" class="w-full bg-[#FFF9FA] rounded-xl px-3 py-2 text-xs text-rosepet-dark border border-rosepet-soft outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-rosepet-muted mb-1">Hex Color</label>
                    <div class="flex items-center gap-1.5">
                        <input type="color" value="#B0C4DE" onchange="document.getElementById('new-hex-input').value = this.value" class="w-8 h-8 rounded-lg cursor-pointer border border-rosepet-soft p-0.5 bg-white">
                        <input type="text" id="new-hex-input" name="color_hex" required value="#B0C4DE" class="w-full bg-[#FFF9FA] rounded-xl px-3 py-2 text-xs text-rosepet-dark border border-rosepet-soft outline-none font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-rosepet-muted mb-1">Harga Normal</label>
                    <input type="number" name="price" required value="{{ $product->variants->first()?->price ?? 349000 }}" class="w-full bg-[#FFF9FA] rounded-xl px-3 py-2 text-xs text-rosepet-dark border border-rosepet-soft outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-rosepet-muted mb-1">Harga Promo</label>
                    <input type="number" name="promo_price" value="{{ $product->variants->first()?->promo_price ?? 319000 }}" class="w-full bg-[#FFF9FA] rounded-xl px-3 py-2 text-xs text-rosepet-dark border border-rosepet-soft outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-rosepet-muted mb-1">Stok</label>
                    <input type="number" name="stock" required value="10" class="w-full bg-[#FFF9FA] rounded-xl px-3 py-2 text-xs text-rosepet-dark border border-rosepet-soft outline-none">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-rosepet-soft text-rosepet-deep hover:bg-rosepet-fresh hover:text-white font-bold text-xs uppercase tracking-wider transition-all">
                    + Simpan Varian Warna
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Script Toggle Edit Varian & CKEditor 5 -->
<script>
    function toggleEditVariant(variantId) {
        const form = document.getElementById('edit-variant-form-' + variantId);
        if (form) {
            form.classList.toggle('hidden');
        }
    }
</script>

<!-- CKEditor 5 Classic CDN Script -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<style>
    .ck-editor__editable_inline {
        min-height: 180px;
        background-color: #FFF9FA !important;
        border-bottom-left-radius: 0.75rem !important;
        border-bottom-right-radius: 0.75rem !important;
        font-size: 13px !important;
        color: #261218 !important;
    }
    .ck-toolbar {
        background-color: #FFFFFF !important;
        border-top-left-radius: 0.75rem !important;
        border-top-right-radius: 0.75rem !important;
        border-color: #FCD8E2 !important;
    }
    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
        border-color: #FCD8E2 !important;
    }
    .ck.ck-editor__main>.ck-editor__editable.ck-focused {
        border-color: #E8617D !important;
        box-shadow: none !important;
    }
</style>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', 'link', '|',
                'bulletedList', 'numberedList', 'blockQuote', '|',
                'undo', 'redo'
            ]
        })
        .catch(error => {
            console.error('Error inisialisasi CKEditor 5:', error);
        });
</script>
@endsection
