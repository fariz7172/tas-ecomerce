@extends('layouts.admin')

@section('title', 'Tambah Tas Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-rosepet-fresh font-bold hover:underline">← Kembali ke Katalog Tas</a>
            <h1 class="font-serif text-3xl font-bold text-rosepet-dark mt-1">Tambah Produk Tas Baru</h1>
            <p class="text-xs text-rosepet-muted">Lengkapi spesifikasi fisik tas, multi-foto WebP, dan optimasi SEO Google.</p>
        </div>
    </div>

    @if ($errors->any())
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-8 border border-rosepet-soft shadow-sm space-y-7">
        @csrf

        <!-- 1. Master Data Tas -->
        <div class="border-b border-rosepet-soft pb-4">
            <h2 class="font-serif text-lg font-bold text-rosepet-dark">1. Informasi Master Produk</h2>
            <p class="text-xs text-rosepet-muted">Nama model tas, kategori, dan narasi craftsmanship.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Nama Tas</label>
                <input type="text" name="name" required placeholder="Contoh: Neverfull MM Monogram" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none font-bold">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Merk / Brand Tas</label>
                <select name="brand_id" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none font-bold">
                    <option value="">-- Pilih Merk / Brand --</option>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }} ({{ $brand->country_origin }})</option>
                    @endforeach
                </select>
            </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Kategori Jenis Tas</label>
                <select name="category_id" required class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5 flex items-center justify-between">
                <span>Deskripsi & Ulasan Lengkap Produk</span>
                <span class="text-[10px] font-bold text-rosepet-fresh bg-rosepet-soft/50 px-2 py-0.5 rounded">✨ Rich Text Editor (CKEditor 5)</span>
            </label>
            <textarea name="description" id="editor" rows="4" placeholder="Ceritakan siluet tas, inspirasi desain, dan keanggunan jahitan tangan...">{{ old('description') }}</textarea>
        </div>

        <!-- 2. Multi-Photo Upload & WebP SEO Alt Texts -->
        <div class="border-b border-rosepet-soft pb-4 pt-3">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-serif text-lg font-bold text-rosepet-dark">2. Galeri Foto Tas (Multi-Upload & WebP Auto-Convert)</h2>
                    <p class="text-xs text-rosepet-muted">Foto otomatis dikonversi ke format <strong>WebP kualitas tinggi (82%)</strong> dan dilengkapi <strong>Tag Alt untuk SEO Google</strong>.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-rosepet-soft text-rosepet-deep text-[10px] font-bold">⚡ WebP + SEO Ready</span>
            </div>
        </div>

        <div class="space-y-4">
            <!-- Drag & Drop / File Input Box -->
            <div class="p-6 rounded-2xl border-2 border-dashed border-rosepet-fresh/40 bg-[#FFF9FA] hover:bg-rosepet-soft/20 transition-colors text-center cursor-pointer relative" onclick="document.getElementById('photo-input').click()">
                <input type="file" name="photos[]" id="photo-input" multiple accept="image/*" class="hidden" onchange="handlePhotoUpload(this)">
                
                <div class="space-y-2">
                    <span class="w-12 h-12 rounded-full bg-white shadow-sm border border-rosepet-soft text-rosepet-fresh inline-flex items-center justify-center text-xl">📸</span>
                    <div>
                        <p class="text-xs font-bold text-rosepet-dark">Klik untuk Pilih Beberapa Foto Tas Sekaligus</p>
                        <p class="text-[11px] text-rosepet-muted">Mendukung JPG, JPEG, PNG, dan WebP (Bisa pilih 2-6 foto detail tas)</p>
                    </div>
                </div>
            </div>

            <!-- Preview & Dynamic SEO Alt Inputs Container -->
            <div id="photo-preview-list" class="space-y-3">
                <!-- Preview items inserted via JavaScript -->
            </div>
        </div>

        <!-- 3. Spesifikasi Fisik Tas -->
        <div class="border-b border-rosepet-soft pb-4 pt-3">
            <h2 class="font-serif text-lg font-bold text-rosepet-dark">3. Spesifikasi Fisik Tas (Bukan Laptop)</h2>
            <p class="text-xs text-rosepet-muted">Dimensi tas fisik, material kulit, berat kosong, dan aksesoris penutup.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Material Kulit/Kain</label>
                <input type="text" name="material" id="product_material" required placeholder="Contoh: Italian Grain Cowhide Leather" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Dimensi (P x L x T)</label>
                <input type="text" name="dimensions_cm" id="product_dimensions" required placeholder="Contoh: 28 x 10 x 20 cm" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Berat Kosong (Gram)</label>
                <input type="number" name="weight_grams" required placeholder="Contoh: 540" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Panjang Tali (Strap Drop)</label>
                <input type="text" name="strap_length" placeholder="Contoh: 95 - 115 cm (Detachable)" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Tipe Penutup</label>
                <input type="text" name="closure_type" required placeholder="Contoh: Magnetic Gold Lock" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Kapasitas (Liter)</label>
                <input type="number" step="0.1" name="capacity_liter" value="5.0" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
            </div>
        </div>

        <!-- 4. Upload File 3D Model GLB (Opsional untuk Interaktif Hero / Detail 3D) -->
        <div class="border-b border-rosepet-soft pb-4 pt-3">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-serif text-lg font-bold text-rosepet-dark">4. Upload Model 3D Tas (Opsional)</h2>
                    <p class="text-xs text-rosepet-muted">Mendukung format file <strong>.glb</strong> (Maks 50MB). Sistem otomatis melakukan optimasi kompresi web.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">🎮 3D WebGL Ready</span>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-[#FFF9FA] border-2 border-dashed border-rosepet-border space-y-2">
            <input type="file" name="model_3d" accept=".glb" class="w-full text-xs text-rosepet-muted file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rosepet-soft file:text-rosepet-deep hover:file:bg-rosepet-fresh hover:file:text-white cursor-pointer">
            <p class="text-[10px] text-rosepet-muted">Format file harus berekstensi <strong>.glb</strong>. File akan otomatis dikompresi di latar belakang saat disimpan.</p>
        </div>

        <!-- 5. Varian Warna Awal & Stok -->
        <div class="border-b border-rosepet-soft pb-4 pt-3">
            <h2 class="font-serif text-lg font-bold text-rosepet-dark">5. Varian Warna Pertama & Harga</h2>
            <p class="text-xs text-rosepet-muted">Setiap produk tas minimal memiliki satu varian warna awal untuk katalog.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Nama Warna</label>
                <input type="text" name="variant_color_name" required placeholder="Contoh: Sakura Blush" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Kode Hex Warna</label>
                <div class="flex items-center gap-2">
                    <input type="color" value="#F8C8D4" onchange="document.getElementById('create-color-hex-input').value = this.value" class="w-10 h-10 rounded-xl cursor-pointer border border-rosepet-soft p-1 bg-white flex-shrink-0">
                    <input type="text" id="create-color-hex-input" name="variant_color_hex" required value="#F8C8D4" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none font-mono font-bold">
                </div>
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Stok Awal Gudang</label>
                <input type="number" name="variant_stock" required value="10" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Harga Normal (Rp)</label>
                <input type="number" name="variant_price" required placeholder="389000" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-rosepet-muted mb-1.5">Harga Promo (Rp)</label>
                <input type="number" name="variant_promo_price" placeholder="349000" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-2.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none font-bold text-rosepet-fresh">
            </div>
        </div>

        <!-- Submit Actions -->
        <div class="pt-6 border-t border-rosepet-soft flex items-center justify-end gap-4">
            <a href="{{ route('admin.products.index') }}" class="px-6 py-3 rounded-xl border border-rosepet-soft text-xs font-bold text-rosepet-muted hover:text-rosepet-dark">Batal</a>
            <button type="submit" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-xs uppercase tracking-wider hover:shadow-lg hover:shadow-rosepet-fresh/30 transition-all flex items-center gap-2">
                <span>Simpan Tas & Proses WebP</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </form>

</div>

<!-- Multi-Photo Preview & Dynamic SEO Alt Generator -->
<script>
    function handlePhotoUpload(input) {
        const previewContainer = document.getElementById('photo-preview-list');
        previewContainer.innerHTML = '';

        if (!input.files || input.files.length === 0) return;

        const prodName = document.getElementById('product_name').value || 'Tas Wanita Eksklusif';
        const prodMat = document.getElementById('product_material').value || 'Kulit Asli';
        const prodDim = document.getElementById('product_dimensions').value || '';

        Array.from(input.files).forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Auto generate smart SEO keywords
                const seoAngles = [
                    'Tampak Depan Siluet Mewah',
                    'Detail Jahitan Tangan & Hardware Emas',
                    'Kompartemen Bagian Dalam & Furing Sutra',
                    'Tampak Samping & Ketebalan Kulit',
                    'Tampak Belakang & Tali Strap',
                    'Kemasan Kotak Hadiah Eksklusif'
                ];
                const angleText = seoAngles[idx] || `Detail Sudut ${idx + 1}`;
                const defaultAlt = `Jual Tas ${prodName} ${prodMat} ${prodDim} - ${angleText} Original Mikael On Shop`;

                const itemDiv = document.createElement('div');
                itemDiv.className = 'p-3.5 rounded-2xl bg-[#FFF9FA] border border-rosepet-soft flex flex-col sm:flex-row items-center gap-4 shadow-sm';
                itemDiv.innerHTML = `
                    <div class="relative w-16 h-16 rounded-xl overflow-hidden bg-white border border-rosepet-soft flex-shrink-0">
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <span class="absolute bottom-0 inset-x-0 bg-black/60 text-white text-[8px] font-mono text-center py-0.5">WebP 82%</span>
                    </div>
                    <div class="flex-1 w-full space-y-1">
                        <div class="flex items-center justify-between">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-rosepet-deep flex items-center gap-1.5">
                                <span>🔍 Tag Alt SEO Google #${idx + 1}</span>
                                ${idx === 0 ? '<span class="bg-rosepet-soft text-rosepet-deep text-[9px] px-2 py-0.5 rounded font-bold">Foto Utama</span>' : ''}
                            </label>
                            <span class="text-[10px] font-mono text-rosepet-muted">${(file.size / 1024).toFixed(1)} KB</span>
                        </div>
                        <input type="text" name="photo_alts[${idx}]" value="${defaultAlt}" class="w-full bg-white rounded-lg px-3 py-1.5 text-xs text-rosepet-dark border border-rosepet-soft focus:border-rosepet-fresh outline-none" placeholder="Masukkan deskripsi SEO alt text...">
                    </div>
                `;
                previewContainer.appendChild(itemDiv);
            };
            reader.readAsDataURL(file);
        });
    }
</script>

<!-- CKEditor 5 Classic CDN Script -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<style>
    /* Styling CKEditor agar selaras dengan tema Petal & Champagne Silk */
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
            ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Normal Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading2', view: 'h3', title: 'Sub-Judul Fitur', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h4', title: 'Poin Penting', class: 'ck-heading_heading3' }
                ]
            }
        })
        .catch(error => {
            console.error('Error inisialisasi CKEditor 5:', error);
        });
</script>
@endsection
