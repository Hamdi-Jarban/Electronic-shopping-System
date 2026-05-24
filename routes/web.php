<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/products',ProductController::class ."@index" );
Route::get('/product/create',ProductController::class ."@create" )->where("product.ceate");

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/products', [ProductController::class, 'index'])->name('product.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');
Route::get('/products/{id}/show', [ProductController::class, 'show'])->name('product.show');

// مسارات الـ API الخلفية التي تستدعيها بالـ JavaScript لتعديل وحذف المنتجات
Route::get('/api/product/{id}/edit', [ProductController::class, 'edit']);
Route::post('/api/products/{id}', [ProductController::class, 'update']);
Route::delete('/api/product/{id}', [ProductController::class, 'destroy']);

require __DIR__.'/auth.php';
