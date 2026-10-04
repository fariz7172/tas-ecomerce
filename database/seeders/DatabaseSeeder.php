<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Fariz Admin',
            'email' => 'admin@ecommercetas.com',
            'password' => bcrypt('password123'),
        ]);

        // 1. Categories
        $catHandbag = Category::create(['name' => 'Handbag & Tote', 'slug' => 'handbag-tote', 'icon' => 'bag-handle']);
        $catShoulder = Category::create(['name' => 'Shoulder & Sling Bag', 'slug' => 'shoulder-sling', 'icon' => 'strap']);
        $catBackpack = Category::create(['name' => 'Backpack & Duffle', 'slug' => 'backpack-duffle', 'icon' => 'backpack']);
        $catClutch = Category::create(['name' => 'Clutch & Pouch', 'slug' => 'clutch-pouch', 'icon' => 'wallet']);

        // 2. Featured 3D Hero Bag: Atelier Royale Tote
        $heroBag = Product::create([
            'category_id' => $catHandbag->id,
            'name' => 'Atelier Royale Structured Tote',
            'slug' => 'atelier-royale-structured-tote',
            'description' => 'Tas jinjing siluet arsitektural terbuat dari kulit sapi asli Grain Leather grade AAA dengan aksen logam kuningan berlapis emas 18K. Dirancang untuk mobilitas wanita dan pria modern yang mengutamakan keanggunan abadi.',
            'material' => 'Full Grain Vegetable-Tanned Italian Leather',
            'dimensions_cm' => '36 x 14 x 28 cm',
            'capacity_liter' => 14.2,
            'weight_grams' => 780,
            'strap_length' => '105 - 125 cm (Adjustable & Detachable)',
            'closure_type' => 'YKK Excella Gold Zipper + Magnet Lock',
            'model_3d_path' => 'models/luxury_bag.glb',
            'is_featured' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $heroBag->id,
            'sku' => 'ROYALE-OBS-01',
            'color_name' => 'Obsidian Black',
            'color_hex' => '#121214',
            'price' => 389000,
            'promo_price' => 349000,
            'stock' => 15,
            'image_path' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80',
        ]);

        ProductVariant::create([
            'product_id' => $heroBag->id,
            'sku' => 'ROYALE-CGN-02',
            'color_name' => 'Cognac Espresso',
            'color_hex' => '#5C2C16',
            'price' => 389000,
            'promo_price' => 349000,
            'stock' => 8,
            'image_path' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=800&q=80',
        ]);

        ProductVariant::create([
            'product_id' => $heroBag->id,
            'sku' => 'ROYALE-IVR-03',
            'color_name' => 'Ivory Cream',
            'color_hex' => '#EDE6DC',
            'price' => 389000,
            'promo_price' => 349000,
            'stock' => 12,
            'image_path' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=800&q=80',
        ]);

        // 3. Product 2: Milano Curve Shoulder Bag
        $p2 = Product::create([
            'category_id' => $catShoulder->id,
            'name' => 'Milano Minimalist Crescent Bag',
            'slug' => 'milano-minimalist-crescent-bag',
            'description' => 'Bentuk bulan sabit ikonik dengan detail lipatan samping yang lentur dan feminin. Sangat pas untuk gaya santai sore hingga jamuan makan malam elegan.',
            'material' => 'Soft Nappa Vegan Leather & Suede Lining',
            'dimensions_cm' => '27 x 8 x 19 cm',
            'capacity_liter' => 4.5,
            'weight_grams' => 420,
            'strap_length' => '48 cm (Fixed Shoulder Drop)',
            'closure_type' => 'Concealed Magnetic Flap',
            'model_3d_path' => 'models/luxury_bag.glb',
            'is_featured' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p2->id,
            'sku' => 'MILANO-SGE-01',
            'color_name' => 'Sage Olive',
            'color_hex' => '#6B7A66',
            'price' => 285000,
            'promo_price' => 259000,
            'stock' => 20,
            'image_path' => 'https://images.unsplash.com/photo-1566150905458-1bf1fc113f0d?auto=format&fit=crop&w=800&q=80',
        ]);

        ProductVariant::create([
            'product_id' => $p2->id,
            'sku' => 'MILANO-BLK-02',
            'color_name' => 'Midnight Onyx',
            'color_hex' => '#0F0F10',
            'price' => 285000,
            'promo_price' => 259000,
            'stock' => 14,
            'image_path' => 'https://images.unsplash.com/photo-1591561954557-26941169b49e?auto=format&fit=crop&w=800&q=80',
        ]);

        // 4. Product 3: Nomad Explorer Waterproof Backpack
        $p3 = Product::create([
            'category_id' => $catBackpack->id,
            'name' => 'Nomad Explorer Waterproof Backpack',
            'slug' => 'nomad-explorer-waterproof-backpack',
            'description' => 'Ransel tahan cuaca berbahan kanvas balistik dengan furing kedap air. Dilengkapi kompartemen botol tumbler tersembunyi dan bantalan punggung ergonomis bernapas.',
            'material' => 'Cordura 1000D Ballistic Nylon + Torin Water-Repellent',
            'dimensions_cm' => '42 x 16 x 30 cm',
            'capacity_liter' => 21.0,
            'weight_grams' => 890,
            'strap_length' => 'Air-Mesh Ergonomic Shoulder Straps',
            'closure_type' => 'Waterproof Seam-Sealed Reverse Zipper',
            'model_3d_path' => 'models/luxury_bag.glb',
            'is_featured' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p3->id,
            'sku' => 'NOMAD-CML-01',
            'color_name' => 'Desert Khaki',
            'color_hex' => '#B89B72',
            'price' => 365000,
            'promo_price' => 325000,
            'stock' => 11,
            'image_path' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=80',
        ]);

        // 5. Dummy Orders for Admin Dashboard Demo
        $order1 = Order::create([
            'order_number' => 'BAG-' . date('Ymd') . '-7721',
            'customer_name' => 'Anindya Putri',
            'customer_phone' => '081288992211',
            'customer_email' => 'anindya@gmail.com',
            'address_line' => 'Jl. Senopati Raya No. 42, Kebayoran Baru',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'courier_code' => 'JNE',
            'courier_service' => 'YES (Yakin Esok Sampai)',
            'shipping_cost' => 18000,
            'subtotal' => 349000,
            'grand_total' => 367000,
            'status' => 'paid',
            'payment_method' => 'QRIS Instan',
            'tracking_number' => 'JNE-CGK-8921820',
            'paid_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'variant_id' => 1,
            'product_name' => 'Atelier Royale Structured Tote',
            'variant_name' => 'Obsidian Black',
            'unit_price' => 349000,
            'quantity' => 1,
            'total_price' => 349000,
        ]);

        $order2 = Order::create([
            'order_number' => 'BAG-' . date('Ymd') . '-4409',
            'customer_name' => 'Bima Wicaksono',
            'customer_phone' => '081399881122',
            'customer_email' => 'bima@outlook.com',
            'address_line' => 'Apartemen Maple Park Tower B Lt. 12',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Utara',
            'district' => 'Sunter Agung',
            'courier_code' => 'SiCepat',
            'courier_service' => 'BEST',
            'shipping_cost' => 14000,
            'subtotal' => 325000,
            'grand_total' => 339000,
            'status' => 'processing',
            'payment_method' => 'BCA Virtual Account',
            'tracking_number' => null,
            'paid_at' => now()->subHours(2),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'variant_id' => 6,
            'product_name' => 'Nomad Explorer Waterproof Backpack',
            'variant_name' => 'Desert Khaki',
            'unit_price' => 325000,
            'quantity' => 1,
            'total_price' => 325000,
        ]);
    }
}
