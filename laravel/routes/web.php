<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\CheckoutController;
use App\Http\Controllers\Store\HomeController;
use App\Http\Controllers\Store\OrderController;
use App\Http\Controllers\Store\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tienda pública
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');

Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/producto/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito', [CartController::class, 'add'])->name('cart.add');
Route::patch('/carrito/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/carrito/cupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/carrito/cupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('checkout.store');

Route::get('/pedido/{order}', [OrderController::class, 'show'])->name('orders.show');

/*
|--------------------------------------------------------------------------
| Acceso al panel de administración
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'show'])->name('login');
    Route::post('/admin/login', [LoginController::class, 'store'])->middleware('throttle:10,1');
});

/*
|--------------------------------------------------------------------------
| Panel de administración
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/', Admin\DashboardController::class)->name('dashboard');

    Route::resource('categorias', Admin\CategoryController::class)
        ->parameters(['categorias' => 'category'])
        ->except('show');

    Route::resource('productos', Admin\ProductController::class)
        ->parameters(['productos' => 'product'])
        ->except('show');

    Route::resource('cupones', Admin\CouponController::class)
        ->parameters(['cupones' => 'coupon'])
        ->except('show');

    Route::get('/pedidos', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/pedidos/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/pedidos/{order}/estado', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/pedidos/{order}/pago', [Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.payment');

    Route::get('/configuracion', [Admin\StoreConfigController::class, 'edit'])->name('config.edit');
    Route::put('/configuracion', [Admin\StoreConfigController::class, 'update'])->name('config.update');
});
