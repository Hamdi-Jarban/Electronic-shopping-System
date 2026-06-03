<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use PHPUnit\Metadata\Group;

Route::get('/', [ShopController::class,'index'])->name('shop.index');
Route::get('/shop', [ShopController::class,'index'])->name('shop.show');
Route::get('/shop/index', [ShopController::class,'index'])->name('cart.index');

Route::prefix('cart')->name('cart.')->group(function ()
{
    // مسارات سلة التسوق
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{variantId}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/update/{itemId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{itemId}', [CartController::class, 'remove'])->name('cart.remove');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('products')->name('product.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
});
//الطلبات
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/order', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/order/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

//  مسارات API للتعديل والحذف
Route::prefix('api/product')->group(function () {
    Route::get('/{id}/edit', [ProductController::class, 'edit']);
    Route::post('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});

require __DIR__ . '/auth.php';
