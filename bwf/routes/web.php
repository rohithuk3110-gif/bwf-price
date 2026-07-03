<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PricingRuleController;
use App\Http\Controllers\Admin\ProductAdminController;

Route::get('/', [ShopController::class, 'home'])->name('home');

Route::get('/staff/login', [LoginController::class, 'show'])->name('login');
Route::post('/staff/login', [LoginController::class, 'login']);
Route::post('/staff/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('staff')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pricing', [PricingRuleController::class, 'index'])->name('pricing');
    Route::put('/pricing/{rule}', [PricingRuleController::class, 'update'])->name('pricing.update');
    Route::get('/products', [ProductAdminController::class, 'index'])->name('products');
    Route::put('/products/{product}', [ProductAdminController::class, 'update'])->name('products.update');
});

Route::post('/quote/{product:slug}', [QuoteController::class, 'store'])->name('quote.store');
Route::get('/quote/{quote:reference}', [QuoteController::class, 'show'])->name('quote.show');
Route::get('/quote/{quote:reference}/pdf', [QuoteController::class, 'pdf'])->name('quote.pdf');

Route::post('/price/{product:slug}', PriceController::class)->name('price');

// product-first catalogue — keep last
Route::get('/{category:slug}', [ShopController::class, 'category'])->name('category');
Route::get('/{category:slug}/{product:slug}', [ShopController::class, 'product'])->name('product');
