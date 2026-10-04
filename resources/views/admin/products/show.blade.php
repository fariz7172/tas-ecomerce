@extends('layouts.admin')

@section('title', 'Detail Produk Tas - ' . $product->name)

@section('content')
@php
    $secureId = \App\Services\SecureIdService::encrypt($product->id);
    $mainVariant = $product->variants->first();
    $price = $mainVariant?->price ?? 0;
    $promoPrice = $mainVariant?->promo_price ?? $price;
@endphp

<div class="max-w-6xl mx-auto space-y-8 pb-12">
    
    <!-- Top Action & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-rosepet-fresh font-bold hover:underline flex items-center gap-1.5">
                <span>← Kembali ke Daftar Katalog</span>
            </a>
            <div class="flex items-center gap-2.5 mt-1.5">
                <h1 class="font-serif text-3xl font-bold text-rosepet-dark">{{ $product->name }}</h1>
                @if($product->is_featured)
                <span class="px-2.5 py-0.5 rounded-full bg-rosepet-soft text-rosepet-deep text-[10px] font-bold">★ 3D Hero Bag</span>
                @endif
            </div>
            <p class="text-xs text-rosepet-muted mt-0.5">
                Merk: <strong class="text-rosepet-dark">{{ $product->brand?->name ?? 'Mikael On Shop' }}</strong> • 
                Kategori: <strong class="text-rosepet-dark">{{ $product->category->name }}</strong>
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.edit', $secureId) }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white font-bold text-xs uppercase tracking-wider hover:shadow-md hover:shadow-rosepet-fresh/30 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit Spesifikasi & Varian</span>
            </a>
        </div>
    </div>

    <!-- Grid Detail Atas (Media & Quick Summary) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Sisi Kiri: Galeri Foto WebP & 3D Interactive Viewport (Col 6) -->
        <div class="lg:col-span-6 space-y-5">
            
            <!-- Tab Switcher Media (Foto Produk vs 3D Model 360°) -->
            <div class="bg-white rounded-3xl p-4 border border-rosepet-soft shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-rosepet-soft pb-3">
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="switchMediaTab('photo')" id="tab-btn-photo" class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                            <span>📷 Foto Galeri</span>
                        </button>
                        @if($product->model_3d_path)
                        <button type="button" onclick="switchMediaTab('3d')" id="tab-btn-3d" class="px-3.5 py-1.5 rounded-xl bg-rosepet-soft text-rosepet-deep hover:bg-rosepet-fresh hover:text-white text-xs font-bold transition-all flex items-center gap-1.5">
                            <span>🎮 Model 3D (360°)</span>
                        </button>
                        @endif
                    </div>

                    <span class="text-[10px] text-rosepet-muted font-medium">
                        {{ $product->model_3d_path ? 'Mendukung Foto & 3D' : 'Mode Foto Standar' }}
                    </span>
                </div>

                <!-- 1. Kontainer Foto Produk (object-contain agar TIDAK terpotong) -->
                <div id="media-photo-container" class="relative w-full h-[420px] rounded-2xl overflow-hidden bg-[#FAF7F8] border border-rosepet-soft/60 flex items-center justify-center p-3">
                    @php
                        $firstImg = $product->images->first()?->image_path ? asset($product->images->first()->image_path) : ($mainVariant?->image_path ?? 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80');
                    @endphp
                    <img id="main-preview-img" src="{{ $firstImg }}" alt="{{ $product->name }}" class="w-full h-full object-contain filter drop-shadow-sm transition-all duration-300">
                </div>

                <!-- 2. Kontainer Interaktif 3D Model Three.js (Jika ada aset 3D) -->
                @if($product->model_3d_path)
                <div id="media-3d-container" class="hidden relative w-full h-[420px] rounded-2xl overflow-hidden bg-gradient-to-b from-[#FFF5F7] to-[#FDF0F3] border border-rosepet-soft/60">
                    <canvas id="detail-3d-canvas" class="w-full h-full cursor-grab active:cursor-grabbing"></canvas>
                    
                    <!-- 3D Overlay Controls & Hint -->
                    <div class="absolute top-3 left-3 px-3 py-1.5 rounded-full bg-white/85 backdrop-blur-md border border-rosepet-soft text-[10px] font-bold text-rosepet-dark flex items-center gap-1.5 shadow-sm pointer-events-none">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Putar & Zoom Bebas 360°</span>
                    </div>

                    <button type="button" onclick="reset3dCamera()" class="absolute bottom-3 right-3 px-3 py-1 rounded-xl bg-white/90 backdrop-blur-md text-[10px] font-bold text-rosepet-dark border border-rosepet-soft hover:bg-rosepet-fresh hover:text-white transition-all shadow-sm">
                        Reset Kamera
                    </button>

                    <div id="model-3d-loader" class="absolute inset-0 bg-[#FFF5F7]/80 backdrop-blur-sm flex flex-col items-center justify-center gap-2">
                        <svg class="animate-spin h-6 w-6 text-rosepet-fresh" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span class="text-[11px] font-bold text-rosepet-dark">Memuat Model 3D Tas...</span>
                    </div>
                </div>
                @endif

                <!-- Thumbnails Strip Galeri Foto -->
                @if($product->images->count() > 1)
                <div class="grid grid-cols-5 gap-2 pt-1 border-t border-rosepet-soft/60">
                    @foreach($product->images as $img)
                    <div onclick="previewPhoto('{{ asset($img->image_path) }}')" class="cursor-pointer h-16 rounded-xl overflow-hidden bg-[#FAF7F8] border border-rosepet-soft hover:border-rosepet-fresh transition-all p-1 flex items-center justify-center">
                        <img src="{{ asset($img->image_path) }}" alt="{{ $img->alt_text }}" class="w-full h-full object-contain">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Box Status & Informasi Teknis Aset 3D -->
            <div class="bg-white rounded-3xl p-5 border border-rosepet-soft shadow-sm space-y-2.5">
                <div class="flex items-center justify-between border-b border-rosepet-soft pb-2.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-rosepet-muted">Status Aset 3D WebGL</span>
                    @if($product->model_3d_path)
                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">✓ Aktif Terhubung</span>
                    @else
                    <span class="text-[10px] font-bold text-rosepet-muted bg-gray-50 px-2.5 py-0.5 rounded-full border">Belum Disediakan</span>
                    @endif
                </div>

                @if($product->model_3d_path)
                <div class="space-y-1.5 text-[11px]">
                    <div class="flex items-center justify-between">
                        <span class="text-rosepet-muted">Lokasi File:</span>
                        <code class="font-mono text-[10px] bg-rosepet-soft/40 text-rosepet-deep px-2 py-0.5 rounded">{{ $product->model_3d_path }}</code>
                    </div>
                    <p class="text-[10px] text-rosepet-muted leading-relaxed">
                        Aset 3D ini telah siap dan otomatis dapat diputar oleh pengunjung pada kanvas 3D interaktif.
                    </p>
                </div>
                @else
                <p class="text-[11px] text-rosepet-muted leading-relaxed">
                    Upload file model berformat <code>.glb</code> pada menu Edit Produk agar tas ini dapat dilihat secara interaktif 3D oleh pembeli.
                </p>
                @endif
            </div>

        </div>

        <!-- Sisi Kanan: Spesifikasi Fisik & Deskripsi Lengkap (Col 6) -->
        <div class="lg:col-span-6 space-y-6">
            
            <!-- Ringkasan Harga & SKU -->
            <div class="bg-white rounded-3xl p-6 border border-rosepet-soft shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-rosepet-muted">Harga Katalog</span>
                        <div class="font-serif font-black text-3xl text-rosepet-dark mt-0.5">
                            Rp {{ number_format($promoPrice, 0, ',', '.') }}
                        </div>
                        @if($price > $promoPrice)
                        <div class="text-xs text-rosepet-muted mt-0.5">
                            Harga Normal: <span class="line-through">Rp {{ number_format($price, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-rosepet-muted">Total Stok Tersedia</span>
                        <div class="font-bold text-xl text-emerald-700 mt-0.5">
                            {{ $product->variants->sum('stock') }} pcs
                        </div>
                        <span class="text-[10px] text-rosepet-muted">{{ $product->variants->count() }} Pilihan Warna</span>
                    </div>
                </div>
            </div>

            <!-- Tabel Spesifikasi Fisik Tas Murni -->
            <div class="bg-white rounded-3xl p-6 border border-rosepet-soft shadow-sm space-y-4">
                <h3 class="font-serif text-base font-bold text-rosepet-dark border-b border-rosepet-soft pb-3 flex items-center justify-between">
                    <span>Spesifikasi Fisik & Material Tas</span>
                    <span class="text-[10px] font-mono text-rosepet-fresh bg-rosepet-soft/50 px-2 py-0.5 rounded">Standar Luxury Bag</span>
                </h3>

                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div class="p-3 rounded-2xl bg-[#FFF9FA] border border-rosepet-soft/60">
                        <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Material Kulit/Kain:</span>
                        <span class="font-bold text-rosepet-dark text-sm mt-0.5 block">{{ $product->material }}</span>
                    </div>

                    <div class="p-3 rounded-2xl bg-[#FFF9FA] border border-rosepet-soft/60">
                        <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Dimensi Fisik (P x L x T):</span>
                        <span class="font-bold text-rosepet-dark text-sm mt-0.5 block">{{ $product->dimensions_cm }}</span>
                    </div>

                    <div class="p-3 rounded-2xl bg-[#FFF9FA] border border-rosepet-soft/60">
                        <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Berat Kosong:</span>
                        <span class="font-bold text-rosepet-dark text-sm mt-0.5 block">{{ $product->weight_grams }} gram</span>
                    </div>

                    <div class="p-3 rounded-2xl bg-[#FFF9FA] border border-rosepet-soft/60">
                        <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Tipe Penutup (Closure):</span>
                        <span class="font-bold text-rosepet-dark text-sm mt-0.5 block">{{ $product->closure_type }}</span>
                    </div>

                    <div class="p-3 rounded-2xl bg-[#FFF9FA] border border-rosepet-soft/60">
                        <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Panjang Tali (Strap):</span>
                        <span class="font-bold text-rosepet-dark text-sm mt-0.5 block">{{ $product->strap_length ?: '-' }}</span>
                    </div>

                    <div class="p-3 rounded-2xl bg-[#FFF9FA] border border-rosepet-soft/60">
                        <span class="text-[10px] uppercase font-bold text-rosepet-muted block">Kapasitas Muat:</span>
                        <span class="font-bold text-rosepet-dark text-sm mt-0.5 block">{{ $product->capacity_liter ? $product->capacity_liter . ' Liter' : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Konten Deskripsi Rich Text CKEditor -->
            <div class="bg-white rounded-3xl p-6 border border-rosepet-soft shadow-sm space-y-3">
                <h3 class="font-serif text-base font-bold text-rosepet-dark border-b border-rosepet-soft pb-3">
                    Deskripsi Lengkap & Ulasan Produk
                </h3>
                <div class="prose prose-sm max-w-none text-rosepet-dark text-xs leading-relaxed space-y-2">
                    {!! $product->description !!}
                </div>
            </div>

        </div>

    </div>

    <!-- Section Varian Warna & SKU Detail (Bawah) -->
    <div class="bg-white rounded-3xl p-6 border border-rosepet-soft shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-rosepet-soft pb-3">
            <div>
                <h3 class="font-serif text-lg font-bold text-rosepet-dark">Daftar Varian Warna & Gudang</h3>
                <p class="text-xs text-rosepet-muted">Setiap pilihan warna memiliki SKU unik untuk kebutuhan fulfillment order.</p>
            </div>
            <a href="{{ route('admin.products.edit', $secureId) }}" class="text-xs font-bold text-rosepet-fresh hover:underline">+ Tambah Varian Baru</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($product->variants as $var)
            <div class="p-4 rounded-2xl bg-[#FFF9FA] border border-rosepet-soft flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl border-2 border-white shadow-sm flex-shrink-0" style="background-color: {{ $var->color_hex }}"></span>
                    <div>
                        <div class="font-bold text-xs text-rosepet-dark">{{ $var->color_name }}</div>
                        <div class="font-mono text-[10px] text-rosepet-muted">SKU: {{ $var->sku }}</div>
                        <div class="text-[11px] font-semibold text-rosepet-fresh mt-0.5">
                            Rp {{ number_format($var->promo_price ?: $var->price, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <span class="text-[10px] text-rosepet-muted uppercase font-bold block">Stok</span>
                    <strong class="text-sm font-bold text-rosepet-dark">{{ $var->stock }} pcs</strong>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

@if($product->model_3d_path)
<!-- Three.js & DRACOLoader untuk Interactive 3D Model di Admin Detail -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/DRACOLoader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

<script>
    let scene, camera, renderer, controls, bagModel, pivotGroup;
    let is3dInitialized = false;

    function switchMediaTab(tab) {
        const photoContainer = document.getElementById('media-photo-container');
        const d3Container = document.getElementById('media-3d-container');
        const btnPhoto = document.getElementById('tab-btn-photo');
        const btn3d = document.getElementById('tab-btn-3d');

        if (tab === 'photo') {
            photoContainer.classList.remove('hidden');
            d3Container.classList.add('hidden');
            btnPhoto.className = 'px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5';
            if (btn3d) btn3d.className = 'px-3.5 py-1.5 rounded-xl bg-rosepet-soft text-rosepet-deep hover:bg-rosepet-fresh hover:text-white text-xs font-bold transition-all flex items-center gap-1.5';
        } else {
            photoContainer.classList.add('hidden');
            d3Container.classList.remove('hidden');
            btnPhoto.className = 'px-3.5 py-1.5 rounded-xl bg-rosepet-soft text-rosepet-deep hover:bg-rosepet-fresh hover:text-white text-xs font-bold transition-all flex items-center gap-1.5';
            if (btn3d) btn3d.className = 'px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5';

            if (!is3dInitialized) {
                init3dViewer();
            } else {
                handleResize();
            }
            setTimeout(handleResize, 100);
            setTimeout(handleResize, 300);
        }
    }

    function previewPhoto(url) {
        const img = document.getElementById('main-preview-img');
        img.style.opacity = '0.4';
        setTimeout(() => {
            img.src = url;
            img.style.opacity = '1';
        }, 150);
        switchMediaTab('photo');
    }

    function init3dViewer() {
        const container = document.getElementById('media-3d-container');
        const canvas = document.getElementById('detail-3d-canvas');
        const loaderEl = document.getElementById('model-3d-loader');

        const width = container.clientWidth || 400;
        const height = container.clientHeight || 420;

        scene = new THREE.Scene();
        scene.background = new THREE.Color(0xFFF7F8);

        camera = new THREE.PerspectiveCamera(40, width / height, 0.1, 100);
        camera.position.set(0, 0.2, 1.8);

        renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: true });
        renderer.setSize(width, height);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        renderer.toneMapping = THREE.ACESFilmicToneMapping;
        renderer.toneMappingExposure = 1.15;
        renderer.shadowMap.enabled = true;

        controls = new THREE.OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.05;
        controls.minDistance = 0.8;
        controls.maxDistance = 4.0;
        controls.maxPolarAngle = Math.PI / 1.7;

        // Pencahayaan Mewah Petal & Gold
        const ambientLight = new THREE.AmbientLight(0xFFFFFF, 1.2);
        scene.add(ambientLight);

        const keyLight = new THREE.DirectionalLight(0xFFF0F5, 1.6);
        keyLight.position.set(2, 4, 3);
        scene.add(keyLight);

        const fillLight = new THREE.DirectionalLight(0xEBB4C4, 0.9);
        fillLight.position.set(-3, 1, 2);
        scene.add(fillLight);

        const rimLight = new THREE.DirectionalLight(0xD4AF37, 0.6);
        rimLight.position.set(0, 3, -3);
        scene.add(rimLight);

        pivotGroup = new THREE.Group();
        scene.add(pivotGroup);

        // GLTF & DRACO Loader
        const dracoLoader = new THREE.DRACOLoader();
        dracoLoader.setDecoderPath('https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/libs/draco/');

        const gltfLoader = new THREE.GLTFLoader();
        gltfLoader.setDRACOLoader(dracoLoader);

        const modelUrl = '{{ asset($product->model_3d_path) }}';

        gltfLoader.load(modelUrl, function (gltf) {
            bagModel = gltf.scene;

            // Pusatkan Pivot ke Center Geometri
            const box = new THREE.Box3().setFromObject(bagModel);
            const center = box.getCenter(new THREE.Vector3());
            const size = box.getSize(new THREE.Vector3());

            bagModel.position.x = -center.x;
            bagModel.position.y = -center.y;
            bagModel.position.z = -center.z;

            const maxDim = Math.max(size.x, size.y, size.z);
            if (maxDim > 0) {
                const scale = 1.0 / maxDim;
                pivotGroup.scale.set(scale, scale, scale);
            }

            pivotGroup.add(bagModel);
            if (loaderEl) loaderEl.style.display = 'none';
        }, undefined, function (error) {
            console.error('Gagal memuat 3D Model:', error);
            if (loaderEl) loaderEl.innerHTML = '<span class="text-xs text-rose-500 font-bold">Gagal memuat model 3D: ' + (error.message || 'Error render') + '</span>';
        });

        is3dInitialized = true;
        animate();

        window.addEventListener('resize', handleResize);
    }

    function handleResize() {
        if (!renderer || !camera) return;
        const container = document.getElementById('media-3d-container');
        if (!container) return;
        const width = container.clientWidth;
        const height = container.clientHeight;
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
        renderer.setSize(width, height);
    }

    function reset3dCamera() {
        if (!controls || !camera) return;
        controls.reset();
        camera.position.set(0, 0.2, 1.8);
    }

    function animate() {
        requestAnimationFrame(animate);
        if (controls) controls.update();
        if (pivotGroup) {
            pivotGroup.rotation.y += 0.003; // Slow luxury auto-rotation
        }
        if (renderer && scene && camera) {
            renderer.render(scene, camera);
        }
    }
</script>
@else
<script>
    function previewPhoto(url) {
        const img = document.getElementById('main-preview-img');
        img.style.opacity = '0.4';
        setTimeout(() => {
            img.src = url;
            img.style.opacity = '1';
        }, 150);
    }
</script>
@endif
@endsection
