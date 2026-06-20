<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use PHPUnit\Metadata\Group;




Route::prefix('admin')->name('admin.')->group(function () {
Route::get('/index', [ProductController::class, 'index'])->name('index');
Route::get('/products/create',[ProductController::class,'create'])->name('products.create');
Route::get('/products/index',[ProductController::class,'index'])->name('products.index');
Route::post('/products/store', [ProductController::class,'store'])->name('products.store');
});


//Route::resource('/products', ProductController::class);

require __DIR__ . '/auth.php';