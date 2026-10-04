<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BagStoreController;
use App\Http\Controllers\AdminCatalogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderHistoryController;

// 1. Landing Page with Three.js 3D Canvas & SEO Sitemap
Route::get('/', [BagStoreController::class, 'index'])->name('home');
Route::get('/product/{slug}', [BagStoreController::class, 'productDetail'])->name('product.detail');
Route::get('/sitemap.xml', [BagStoreController::class, 'sitemap'])->name('seo.sitemap');

// 2. Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Socialite OAuth Routes (Google & Facebook)
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    Route::get('/auth/facebook', [AuthController::class, 'redirectToFacebook'])->name('auth.facebook');
    Route::get('/auth/facebook/callback', [AuthController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// 3. User Protected Routes (Wishlist & Checkout Berbasis Auth)
Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{productId}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Riwayat Pesanan Saya
    Route::get('/orders', [OrderHistoryController::class, 'index'])->name('orders.history');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.page');
    Route::post('/checkout', [BagStoreController::class, 'checkout'])
        ->middleware('throttle:6,1')
        ->name('checkout.process');
});

// 4. Instant APIs & Order Success
Route::get('/api/locations/search', [BagStoreController::class, 'searchLocation'])->name('api.locations.search');
Route::post('/api/shipping/calculate', [BagStoreController::class, 'calculateShippingCost'])->name('api.shipping.calculate');
Route::post('/api/midtrans/webhook', [BagStoreController::class, 'midtransWebhook'])->name('api.midtrans.webhook');
Route::get('/api/order/{order_number}/qris-image', [BagStoreController::class, 'streamQrisImage'])->name('api.order.qris_image');
Route::get('/order/success/{order_number}', [BagStoreController::class, 'orderSuccess'])->name('order.success');

// 5. Admin Fulfillment & Order Management (Protected by Admin Auth Middleware)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [BagStoreController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/order/{id}/show', [BagStoreController::class, 'adminOrderShow'])->name('admin.order.show');
    Route::get('/order/{id}/invoice', [BagStoreController::class, 'adminOrderInvoice'])->name('admin.order.invoice');
    Route::post('/order/{id}/ship', [BagStoreController::class, 'shipOrder'])->name('admin.order.ship');
    Route::post('/order/{id}/status', [BagStoreController::class, 'updateOrderStatus'])->name('admin.order.status');
    Route::post('/order/{id}/sync-midtrans', [BagStoreController::class, 'syncMidtransStatus'])->name('admin.order.sync_midtrans');
    Route::get('/order/{id}/thermal-label', [BagStoreController::class, 'printThermalLabel'])->name('admin.order.thermal_label');

    // Category CRUD
    Route::get('/categories', [AdminCatalogController::class, 'categoriesIndex'])->name('admin.categories.index');
    Route::post('/categories', [AdminCatalogController::class, 'categoryStore'])->name('admin.categories.store');
    Route::put('/categories/{id}', [AdminCatalogController::class, 'categoryUpdate'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [AdminCatalogController::class, 'categoryDestroy'])->name('admin.categories.destroy');

    // Brand CRUD (Merk Tas)
    Route::get('/brands', [AdminCatalogController::class, 'brandsIndex'])->name('admin.brands.index');
    Route::post('/brands', [AdminCatalogController::class, 'brandStore'])->name('admin.brands.store');
    Route::delete('/brands/{id}', [AdminCatalogController::class, 'brandDestroy'])->name('admin.brands.destroy');

    // Product & Variant CRUD
    Route::get('/products', [AdminCatalogController::class, 'productsIndex'])->name('admin.products.index');
    Route::get('/products/create', [AdminCatalogController::class, 'productCreate'])->name('admin.products.create');
    Route::post('/products', [AdminCatalogController::class, 'productStore'])->name('admin.products.store');
    Route::get('/products/{id}', [AdminCatalogController::class, 'productShow'])->name('admin.products.show');
    Route::get('/products/{id}/edit', [AdminCatalogController::class, 'productEdit'])->name('admin.products.edit');
    Route::put('/products/{id}', [AdminCatalogController::class, 'productUpdate'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminCatalogController::class, 'productDestroy'])->name('admin.products.destroy');
    Route::delete('/products/images/{id}', [AdminCatalogController::class, 'productImageDestroy'])->name('admin.products.images.destroy');
    Route::post('/products/images/{id}/primary', [AdminCatalogController::class, 'productImageSetPrimary'])->name('admin.products.images.primary');

    // Variant Operations
    Route::post('/products/{id}/variants', [AdminCatalogController::class, 'variantStore'])->name('admin.variants.store');
    Route::put('/variants/{id}', [AdminCatalogController::class, 'variantUpdate'])->name('admin.variants.update');
    Route::delete('/variants/{id}', [AdminCatalogController::class, 'variantDestroy'])->name('admin.variants.destroy');
});
