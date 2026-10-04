<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // 2. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('material')->nullable(); // e.g. Genuine Nappa Leather, Premium Canvas, PU Leather
            $table->string('dimensions_cm')->nullable(); // e.g. 32 x 12 x 26 cm
            $table->decimal('capacity_liter', 4, 1)->nullable();
            $table->integer('weight_grams')->default(650);
            $table->string('strap_length')->nullable(); // e.g. 95 - 120 cm (Removable & Adjustable)
            $table->string('closure_type')->nullable(); // e.g. YKK Metal Zipper, Magnetic Clasp
            $table->string('model_3d_path')->nullable(); // e.g. models/luxury_bag.glb
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Product Variants (Color, SKU, Stock, Pricing)
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('color_name'); // e.g. Obsidian Black, Cognac Brown, Sage Green
            $table->string('color_hex', 20)->default('#1A1A1A');
            $table->decimal('price', 12, 2);
            $table->decimal('promo_price', 12, 2)->nullable();
            $table->integer('stock')->default(10);
            $table->string('image_path')->nullable();
            $table->timestamps();
        });

        // 4. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('address_line');
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('courier_code')->default('JNE');
            $table->string('courier_service')->default('REG');
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('grand_total', 12, 2);
            $table->enum('status', ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // 5. Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->string('product_name');
            $table->string('variant_name');
            $table->decimal('unit_price', 12, 2);
            $table->integer('quantity');
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
