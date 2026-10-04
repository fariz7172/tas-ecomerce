<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Services\SecureIdService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminCatalogController extends Controller
{
    // ==========================================
    // 1. CATEGORY CRUD
    // ==========================================
    public function categoriesIndex()
    {
        $categories = Category::withCount('products')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'icon' => 'nullable|string|max:50',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'icon' => $validated['icon'] ?? 'bag',
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori baru berhasil ditambahkan, sayang!');
    }

    public function categoryUpdate(Request $request, $id)
    {
        $realId = SecureIdService::decrypt($id) ?? (is_numeric($id) ? (int)$id : null);
        if (!$realId) abort(404, 'Kategori tidak valid');

        $category = Category::findOrFail($realId);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:50',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'icon' => $validated['icon'] ?? $category->icon,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori tas berhasil diperbarui!');
    }

    public function categoryDestroy($id)
    {
        $realId = SecureIdService::decrypt($id) ?? (is_numeric($id) ? (int)$id : null);
        if (!$realId) abort(404, 'Kategori tidak valid');

        $category = Category::findOrFail($realId);

        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Kategori ini tidak dapat dihapus karena masih memiliki produk aktif di dalamnya.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }

    // ==========================================
    // 2. PRODUCT CRUD
    // ==========================================
    public function productsIndex(Request $request)
    {
        $query = Product::with(['category', 'brand', 'variants', 'images'])->latest();

        // 1. Filter Pencarian Nama / Material / Deskripsi
        if ($request->filled('q')) {
            $keyword = trim($request->query('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('material', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // 2. Filter Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        // 3. Filter Merk / Brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->query('brand_id'));
        }

        // 4. Pagination 10 produk per halaman
        $products = $query->paginate(10)->withQueryString();

        $categories = Category::orderBy('name')->get();
        $brands = \App\Models\Brand::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function productCreate()
    {
        $categories = Category::orderBy('name')->get();
        $brands = \App\Models\Brand::orderBy('name')->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function productStore(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:150|unique:products,name',
            'description' => 'required|string',
            'material' => 'required|string|max:150',
            'dimensions_cm' => 'required|string|max:50',
            'capacity_liter' => 'nullable|numeric',
            'weight_grams' => 'required|integer',
            'strap_length' => 'nullable|string|max:100',
            'closure_type' => 'required|string|max:100',
            'model_3d' => 'nullable|file|max:51200', // File .glb maks 50MB
            'is_featured' => 'nullable|boolean',
            // Default Variant 1
            'variant_color_name' => 'required|string|max:50',
            'variant_color_hex' => 'required|string|max:20',
            'variant_price' => 'required|numeric|min:0',
            'variant_promo_price' => 'nullable|numeric|min:0',
            'variant_stock' => 'required|integer|min:0',
            'variant_image_url' => 'nullable|url',
            // Multiple Photos & SEO ALT Texts
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:8192',
            'photo_alts' => 'nullable|array',
        ]);

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'material' => $validated['material'],
            'dimensions_cm' => $validated['dimensions_cm'],
            'capacity_liter' => $validated['capacity_liter'] ?? 0,
            'weight_grams' => $validated['weight_grams'],
            'strap_length' => $validated['strap_length'] ?? '-',
            'closure_type' => $validated['closure_type'],
            'model_3d_path' => null,
            'is_featured' => $request->has('is_featured'),
            'is_active' => true,
        ]);

        // Upload & Auto-Compress 3D Model GLB
        if ($request->hasFile('model_3d')) {
            $modelFile = $request->file('model_3d');
            $modelName = 'bag_3d_' . $product->id . '_' . time() . '.glb';
            $destinationDir = public_path('models');
            if (!file_exists($destinationDir)) {
                mkdir($destinationDir, 0755, true);
            }
            $targetPath = $destinationDir . DIRECTORY_SEPARATOR . $modelName;

            $modelFile->move($destinationDir, $modelName);
            $this->optimizeGlbModel($targetPath);

            $product->update([
                'model_3d_path' => 'models/' . $modelName
            ]);
        }

        // Process Uploaded Photos to WebP
        $firstWebpUrl = null;
        if ($request->hasFile('photos')) {
            $storageDir = storage_path('app/public/products');
            if (!file_exists($storageDir)) {
                mkdir($storageDir, 0755, true);
            }

            foreach ($request->file('photos') as $index => $uploadedFile) {
                if (!$uploadedFile->isValid()) continue;

                $filename = 'bag_' . Str::slug($product->name) . '_' . uniqid() . '.webp';
                $destinationPath = $storageDir . DIRECTORY_SEPARATOR . $filename;

                $this->convertToWebp($uploadedFile->getPathname(), $destinationPath, 1600, 82);

                // Build Optimized SEO Alt Text
                $customAlt = $request->input("photo_alts.{$index}");
                $seoAlt = $customAlt ?: "Tas {$product->name} Bahan {$product->material} Dimensi {$product->dimensions_cm} - Mikael On Shop";

                $imagePath = 'storage/products/' . $filename;
                if ($index === 0) {
                    $firstWebpUrl = asset($imagePath);
                }

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'alt_text' => $seoAlt,
                    'is_primary' => ($index === 0),
                    'sort_order' => $index,
                ]);
            }
        }

        // Generate SKU & initial variant
        $sku = strtoupper(substr(Str::slug($product->name), 0, 6) . '-' . substr(md5(uniqid()), 0, 4));

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => $sku,
            'color_name' => $validated['variant_color_name'],
            'color_hex' => $validated['variant_color_hex'],
            'price' => $validated['variant_price'],
            'promo_price' => $validated['variant_promo_price'] ?: $validated['variant_price'],
            'stock' => $validated['variant_stock'],
            'image_path' => $firstWebpUrl ?: ($validated['variant_image_url'] ?? 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80'),
        ]);

        return redirect()->route('admin.products.index')->with('success', "Tas {$product->name} dan foto WebP teroptimasi SEO berhasil ditambahkan!");
    }

    public function productShow($id)
    {
        $realId = SecureIdService::decrypt($id) ?? (is_numeric($id) ? (int)$id : null);
        if (!$realId) {
            abort(404, 'Produk tas tidak ditemukan atau token tidak valid.');
        }

        $product = Product::with(['category', 'brand', 'variants', 'images'])->findOrFail($realId);
        return view('admin.products.show', compact('product'));
    }

    public function productEdit($id)
    {
        $realId = SecureIdService::decrypt($id) ?? (is_numeric($id) ? (int)$id : null);
        if (!$realId) {
            abort(404, 'Produk tas tidak ditemukan atau token tidak valid.');
        }

        $product = Product::with(['variants', 'images'])->findOrFail($realId);
        $categories = Category::orderBy('name')->get();
        $brands = \App\Models\Brand::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function productUpdate(Request $request, $id)
    {
        $realId = SecureIdService::decrypt($id) ?? (is_numeric($id) ? (int)$id : null);
        if (!$realId) {
            abort(404, 'Token produk tidak valid.');
        }

        $product = Product::findOrFail($realId);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:150|unique:products,name,' . $product->id,
            'description' => 'required|string',
            'material' => 'required|string|max:150',
            'dimensions_cm' => 'required|string|max:50',
            'capacity_liter' => 'nullable|numeric',
            'weight_grams' => 'required|integer',
            'strap_length' => 'nullable|string|max:100',
            'closure_type' => 'required|string|max:100',
            'model_3d' => 'nullable|file|max:51200', // File .glb maks 50MB
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            // Optional additional photos during edit
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:8192',
            'photo_alts' => 'nullable|array',
        ]);

        $product->update([
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'material' => $validated['material'],
            'dimensions_cm' => $validated['dimensions_cm'],
            'capacity_liter' => $validated['capacity_liter'] ?? 0,
            'weight_grams' => $validated['weight_grams'],
            'strap_length' => $validated['strap_length'] ?? '-',
            'closure_type' => $validated['closure_type'],
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
        ]);

        // Upload & Update 3D Model GLB
        if ($request->hasFile('model_3d')) {
            $modelFile = $request->file('model_3d');
            $modelName = 'bag_3d_' . $product->id . '_' . time() . '.glb';
            $destinationDir = public_path('models');
            if (!file_exists($destinationDir)) {
                mkdir($destinationDir, 0755, true);
            }
            $targetPath = $destinationDir . DIRECTORY_SEPARATOR . $modelName;

            if ($product->model_3d_path && file_exists(public_path($product->model_3d_path)) && !str_contains($product->model_3d_path, 'luxury_bag.glb') && !str_contains($product->model_3d_path, 'lv.glb')) {
                @unlink(public_path($product->model_3d_path));
            }

            $modelFile->move($destinationDir, $modelName);
            $this->optimizeGlbModel($targetPath);

            $product->update([
                'model_3d_path' => 'models/' . $modelName
            ]);
        }

        // Upload additional photos if any
        if ($request->hasFile('photos')) {
            $storageDir = storage_path('app/public/products');
            if (!file_exists($storageDir)) {
                mkdir($storageDir, 0755, true);
            }

            foreach ($request->file('photos') as $index => $uploadedFile) {
                if (!$uploadedFile->isValid()) continue;

                $filename = 'bag_' . Str::slug($product->name) . '_' . uniqid() . '.webp';
                $destinationPath = $storageDir . DIRECTORY_SEPARATOR . $filename;

                $this->convertToWebp($uploadedFile->getPathname(), $destinationPath, 1600, 82);

                $customAlt = $request->input("photo_alts.{$index}");
                $seoAlt = $customAlt ?: "Tas {$product->name} Bahan {$product->material} Dimensi {$product->dimensions_cm} - Detail Mikael On Shop";

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'storage/products/' . $filename,
                    'alt_text' => $seoAlt,
                    'is_primary' => false,
                    'sort_order' => $product->images()->count() + $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', "Spesifikasi tas {$product->name} berhasil diperbarui!");
    }

    public function productDestroy($id)
    {
        $realId = SecureIdService::decrypt($id) ?? (is_numeric($id) ? (int)$id : null);
        if (!$realId) abort(404, 'Token produk tidak valid.');

        $product = Product::with('images')->findOrFail($realId);
        $name = $product->name;

        // Delete physical webp files
        foreach ($product->images as $img) {
            $relativePath = str_replace('storage/', '', $img->image_path);
            Storage::disk('public')->delete($relativePath);
            $img->delete();
        }

        $product->variants()->delete();
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', "Tas {$name} dan seluruh asetnya berhasil dihapus!");
    }

    // ==========================================
    // 3. VARIANT STORE / DESTROY
    // ==========================================
    public function variantStore(Request $request, $productId)
    {
        $realId = SecureIdService::decrypt($productId) ?? (is_numeric($productId) ? (int)$productId : null);
        if (!$realId) abort(404, 'Produk tidak valid');

        $product = Product::findOrFail($realId);

        $validated = $request->validate([
            'color_name' => 'required|string|max:50',
            'color_hex' => 'required|string|max:20',
            'price' => 'required|numeric|min:0',
            'promo_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image_path' => 'nullable|url',
        ]);

        $sku = strtoupper(substr(Str::slug($product->name), 0, 5) . '-' . substr(Str::slug($validated['color_name']), 0, 3) . '-' . rand(10, 99));

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => $sku,
            'color_name' => $validated['color_name'],
            'color_hex' => $validated['color_hex'],
            'price' => $validated['price'],
            'promo_price' => $validated['promo_price'] ?: $validated['price'],
            'stock' => $validated['stock'],
            'image_path' => $validated['image_path'] ?? 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80',
        ]);

        return redirect()->back()->with('success', "Varian warna {$validated['color_name']} berhasil ditambahkan ke tas!");
    }

    public function variantUpdate(Request $request, $id)
    {
        $realId = SecureIdService::decrypt($id) ?? (is_numeric($id) ? (int)$id : null);
        if (!$realId) abort(404, 'Varian tidak valid');

        $variant = ProductVariant::findOrFail($realId);

        $validated = $request->validate([
            'color_name' => 'required|string|max:50',
            'color_hex' => 'required|string|max:20',
            'price' => 'required|numeric|min:0',
            'promo_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $variant->update([
            'color_name' => $validated['color_name'],
            'color_hex' => $validated['color_hex'],
            'price' => $validated['price'],
            'promo_price' => $validated['promo_price'] ?: $validated['price'],
            'stock' => $validated['stock'],
        ]);

        return redirect()->back()->with('success', "Varian {$variant->color_name} berhasil diperbarui!");
    }

    public function variantDestroy($id)
    {
        $realId = SecureIdService::decrypt($id) ?? (is_numeric($id) ? (int)$id : null);
        if (!$realId) abort(404, 'Varian tidak valid');

        $variant = ProductVariant::findOrFail($realId);
        $variant->delete();

        return redirect()->back()->with('success', 'Varian warna berhasil dihapus!');
    }

    // ==========================================
    // 4. BRAND CRUD (MERK TAS)
    // ==========================================
    public function brandsIndex()
    {
        $brands = \App\Models\Brand::withCount('products')->orderBy('name')->get();
        return view('admin.brands.index', compact('brands'));
    }

    public function brandStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:brands,name',
            'country_origin' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        \App\Models\Brand::create($validated);

        return back()->with('success', 'Merk tas "' . $validated['name'] . '" berhasil ditambahkan ke katalog!');
    }

    public function brandDestroy($id)
    {
        $realId = SecureIdService::decrypt($id) ?? (is_numeric($id) ? (int)$id : null);
        if (!$realId) abort(404, 'Merk tidak valid');

        $brand = \App\Models\Brand::withCount('products')->findOrFail($realId);
        if ($brand->products_count > 0) {
            return back()->with('error', 'Merk tidak dapat dihapus karena masih memiliki ' . $brand->products_count . ' produk tas terkait.');
        }

        $brand->delete();
        return back()->with('success', 'Merk tas berhasil dihapus.');
    }

    // ==========================================
    // 4. IMAGE CONVERTER & OPTIMIZATION HELPER
    // ==========================================
    protected function convertToWebp(string $sourcePath, string $destinationPath, int $maxWidth = 1600, int $quality = 82): bool
    {
        $imageInfo = @getimagesize($sourcePath);
        if (!$imageInfo) return false;

        $mime = $imageInfo['mime'];
        $srcImage = null;

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $srcImage = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $srcImage = @imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $srcImage = @imagecreatefromwebp($sourcePath);
                break;
            default:
                return false;
        }

        if (!$srcImage) return false;

        $origWidth = imagesx($srcImage);
        $origHeight = imagesy($srcImage);

        // Calculate aspect ratio downscaling
        if ($origWidth > $maxWidth) {
            $targetWidth = $maxWidth;
            $targetHeight = (int) round(($origHeight / $origWidth) * $maxWidth);
        } else {
            $targetWidth = $origWidth;
            $targetHeight = $origHeight;
        }

        $dstImage = imagecreatetruecolor($targetWidth, $targetHeight);

        // Preserve alpha transparency for PNG/WebP
        imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);
        $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
        imagefilledrectangle($dstImage, 0, 0, $targetWidth, $targetHeight, $transparent);

        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $origWidth, $origHeight);

        // Save as WebP
        $result = imagewebp($dstImage, $destinationPath, $quality);

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return $result;
    }

    // ==========================================
    // 5. 3D GLB MODEL OPTIMIZER (SMART ENGINE)
    // ==========================================
    protected function optimizeGlbModel(string $filePath): void
    {
        // Berikan waktu eksekusi yang leluasa untuk proses kompresi
        @set_time_limit(180);

        try {
            $tempOptimized = $filePath . '.opt.glb';
            $blenderPath = 'D:\\program file\\Blender\\blender.exe';
            $engineScript = app_path('Services/compress_glb_engine.py');

            // 1. Coba gunakan Blender Engine jika tersedia (Sangat Cepat & Tanpa Perlu Koneksi Internet)
            if (file_exists($blenderPath) && file_exists($engineScript)) {
                $command = sprintf(
                    '"%s" --background --python "%s" -- %s %s',
                    $blenderPath,
                    $engineScript,
                    escapeshellarg($filePath),
                    escapeshellarg($tempOptimized)
                );
                @exec($command, $output, $returnCode);

                if ($returnCode === 0 && file_exists($tempOptimized) && filesize($tempOptimized) > 0) {
                    @unlink($filePath);
                    @rename($tempOptimized, $filePath);
                    return;
                }
            }

            // 2. Fallback: Coba gltf-transform jika terpasang lokal tanpa mendownload ulang
            $commandNpx = sprintf(
                'npx --no-install @gltf-transform/cli optimize %s %s --compress draco',
                escapeshellarg($filePath),
                escapeshellarg($tempOptimized)
            );
            @exec($commandNpx, $outputNpx, $returnCodeNpx);

            if ($returnCodeNpx === 0 && file_exists($tempOptimized) && filesize($tempOptimized) > 0) {
                @unlink($filePath);
                @rename($tempOptimized, $filePath);
                return;
            }

            // Jika semua optimizer tidak aktif, bersihkan file temporary dan tetap gunakan file asli
            if (file_exists($tempOptimized)) {
                @unlink($tempOptimized);
            }
        } catch (\Throwable $e) {
            \Log::warning('GLB auto-compression skipped: ' . $e->getMessage());
        }
    }
}
