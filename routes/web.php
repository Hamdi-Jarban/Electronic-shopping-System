<?php

use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Customer\CartController as CustomerCartController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\ShopController;
use Illuminate\Support\Facades\Route;
use PHPUnit\Metadata\Group;




Route::get('/', [ShopController::class, 'index'])->name('index');


Route::prefix('admin')->name('admin.')->group(function () {
Route::get('/index', [AdminProductController::class, 'index'])->name('index');
Route::get('/products/create',[AdminProductController::class,'create'])->name('products.create');
Route::get('/products/index',[AdminProductController::class,'index'])->name('products.index');
Route::post('/products/store', [AdminProductController::class,'store'])->name('products.store');
});

Route::post('/cart/add', [CartController::class,'add'])->name('cart.add');

//Route::resource('/products', ProductController::class);

require __DIR__ . '/auth.php';