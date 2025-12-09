<?php

use App\Http\Controllers\{
    AdminController,
    AdminOrderController,
    AdminProductController,
    CartController,
    DiscountController,
    FavoriteController,
    HomeController,
    LoginController,
    MenuController,
    OrderController,
    ProductController,
    ProfileController,
    RegisterController,
    ReservasiController,
    ReviewController
};
use Illuminate\Support\Facades\Route;

// Rute Publik - Halaman yang dapat diakses tanpa login
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
Route::get('/about', fn() => view('pages.about'))->name('about');
Route::get('/contact', fn() => view('pages.contact'))->name('contact');

// Rute Autentikasi - Login & Registrasi (hanya untuk guest)
Route::middleware('guest')->group(function () {
    Route::get('/register', fn() => view('register'))->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/login', fn() => view('login'))->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// Rute yang Memerlukan Autentikasi
Route::middleware('auth')->group(function () {
    // Menu & Produk
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::get('/menu/{product}', [ProductController::class, 'show'])->name('menu.show');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    
    // Manajemen Reservasi
    Route::prefix('reservasi')->name('reservasi.')->group(function () {
        Route::get('/create', [ReservasiController::class, 'create'])->name('create');
        Route::post('/', [ReservasiController::class, 'store'])->name('store');
        Route::get('/list', [ReservasiController::class, 'index'])->name('list');
        Route::get('/{reservasi}', [ReservasiController::class, 'show'])->name('show');
        Route::get('/{reservasi}/confirmation', [ReservasiController::class, 'confirmation'])->name('confirmation');
        Route::get('/{reservasi}/edit', [ReservasiController::class, 'edit'])->name('edit');
        Route::put('/{reservasi}', [ReservasiController::class, 'update'])->name('update');
        Route::delete('/{reservasi}', [ReservasiController::class, 'destroy'])->name('destroy');
    });
    
    Route::get('/api/reservasi/check-availability', [ReservasiController::class, 'checkAvailability'])->name('reservasi.checkAvailability');
    
    // Review & Kustomisasi Produk
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/customizations', fn() => response()->json(['success' => true]))->name('customizations.store');
    
    // Favorit Produk
    Route::post('/products/{product}/favorite', [FavoriteController::class, 'toggle'])->name('products.favorite');
    Route::get('/favorites', [FavoriteController::class, 'show'])->name('favorites.index');
    
    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/{user}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
    
    // Keranjang Belanja
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'show'])->name('show');
        Route::post('/sync', [CartController::class, 'syncGuest'])->name('sync');
        Route::post('/items', [CartController::class, 'addItem'])->name('items.add');
        Route::put('/items/{item}', [CartController::class, 'updateItem'])->name('items.update');
        Route::delete('/items/{item}', [CartController::class, 'removeItem'])->name('items.remove');
    });
    
    // Checkout & Pesanan
    Route::match(['get', 'post'], '/checkout', fn() => view('checkout'))->name('checkout');
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/confirmation', [OrderController::class, 'confirmation'])->name('confirmation');
        Route::get('/{order}/payment', [OrderController::class, 'showPayment'])->name('payment');
        Route::get('/{order}/pay-whatsapp', [OrderController::class, 'payWithWhatsapp'])->name('pay.whatsapp');
        Route::post('/{order}/reorder', [OrderController::class, 'reorder'])->name('reorder');
    });
    
    // Promosi
    Route::prefix('promotions')->name('promotions.')->group(function () {
        Route::get('/', fn() => view('admin.promotions.index'))->name('index');
        Route::get('/create', fn() => view('admin.promotions.create'))->name('create');
        Route::post('/', fn() => response()->json(['success' => true]))->name('store');
        Route::get('/{promotion}/edit', fn() => view('admin.promotions.edit'))->name('edit');
        Route::put('/{promotion}', fn() => response()->json(['success' => true]))->name('update');
        Route::delete('/{promotion}', fn() => response()->json(['success' => true]))->name('destroy');
    });
    
    // Ekspor untuk Pemilik
    Route::prefix('owner/export')->name('owner.export.')->group(function () {
        Route::get('/pdf', fn() => response()->json(['success' => true]))->name('pdf');
        Route::get('/excel', fn() => response()->json(['success' => true]))->name('excel');
    });
});

// Rute Admin - Memerlukan autentikasi dan role admin/pemilik
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Manajemen Produk
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [AdminController::class, 'products'])->name('index');
        Route::get('/create', [AdminController::class, 'createProduct'])->name('create');
        Route::post('/', [AdminController::class, 'storeProduct'])->name('store');
        Route::get('/{product}/edit', [AdminController::class, 'editProduct'])->name('edit');
        Route::put('/{product}', [AdminController::class, 'updateProduct'])->name('update');
        Route::patch('/{product}/toggle', [AdminController::class, 'toggleProductStatus'])->name('toggle');
        Route::delete('/{product}', [AdminController::class, 'deleteProduct'])->name('delete');
    });
    
    // Manajemen Varian Produk
    Route::prefix('variants')->name('variants.')->group(function () {
        Route::get('/', fn() => view('admin.variants.index'))->name('index');
        Route::post('/', fn() => response()->json(['success' => true]))->name('store');
        Route::put('/{variant}', fn() => response()->json(['success' => true]))->name('update');
        Route::delete('/{variant}', fn() => response()->json(['success' => true]))->name('destroy');
    });
    
    // Manajemen Kustomisasi
    Route::post('/customizations/{customization}/toggle', fn() => response()->json(['success' => true]))->name('customizations.toggle');
    Route::post('/customizations/{customization}/import', fn() => response()->json(['success' => true]))->name('customizations.import');
    
    // Manajemen Pesanan
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        Route::patch('/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('updateStatus');
        Route::patch('/{order}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('updatePaymentStatus');
        Route::post('/{order}/whatsapp', [AdminOrderController::class, 'sendWhatsappNotification'])->name('whatsapp');
        Route::get('/export/csv', [AdminOrderController::class, 'export'])->name('export');
    });
    
    Route::get('/order-dashboard', [AdminOrderController::class, 'dashboard'])->name('order-dashboard');
    
    // Analitik & Laporan
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'analytics'])->name('index');
        Route::get('/export', [AdminOrderController::class, 'exportAnalytics'])->name('export');
    });
    
    // Manajemen Inventori
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [AdminProductController::class, 'inventory'])->name('index');
        Route::patch('/{product}/stock', [AdminProductController::class, 'updateStock'])->name('updateStock');
        Route::post('/bulk-update', [AdminProductController::class, 'bulkUpdateStock'])->name('bulkUpdate');
        Route::post('/{product}/threshold', [AdminProductController::class, 'setLowStockAlert'])->name('setThreshold');
        Route::get('/restock-needed', [AdminProductController::class, 'getRestockNeeded'])->name('restockNeeded');
        Route::get('/export/csv', [AdminProductController::class, 'exportInventory'])->name('export');
    });
    
    // Manajemen Reservasi Admin
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [AdminController::class, 'reservations'])->name('index');
        Route::patch('/{reservasi}/confirm', [AdminController::class, 'confirmReservation'])->name('confirm');
        Route::patch('/{reservasi}/cancel', [AdminController::class, 'cancelReservation'])->name('cancel');
    });
    
    // Manajemen Pengguna
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('index');
        Route::patch('/{user}/ban', [AdminController::class, 'toggleBan'])->name('ban');
    });
});

// API Publik
Route::get('/api/products', [ProductController::class, 'apiList'])->name('products.api');
Route::get('/products/{product}/reviews', [ReviewController::class, 'getProductReviews'])->name('products.reviews');
Route::get('/favorites/check/{product}', [FavoriteController::class, 'checkFavorite'])->name('favorites.check');

// API Diskon
Route::prefix('api/discounts')->name('discounts.')->group(function () {
    Route::post('/validate', [DiscountController::class, 'validate'])->name('validate');
    Route::post('/usage', [DiscountController::class, 'recordUsage'])->middleware('auth')->name('usage');
    Route::get('/active', [DiscountController::class, 'getActive'])->name('active');
});
