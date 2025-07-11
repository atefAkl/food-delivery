<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\ReviewController;

Route::prefix('admin')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('auth');
        Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');
    });

    // جميع صفحات الادمن هنا محمية بauth:admin ولا يمكن الوصول إليها إلا بعد تسجيل الدخول
    Route::middleware('auth:admin')->name('admin-')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // ضع هنا بقية مسارات لوحة الإدارة


        Route::prefix('admins')->name('admins-')->group(function () {
            Route::get('list', [AdminController::class, 'index'])->name('list');
            Route::post('store', [AdminController::class, 'store'])->name('store');
            Route::get('show/{id}', [AdminController::class, 'show'])->name('show');
            Route::put('update/{id}', [AdminController::class, 'update'])->name('update');
            Route::delete('delete/{id}', [AdminController::class, 'destroy'])->name('delete');
            Route::patch('approve/{id}', [AdminController::class, 'approve'])->name('approve');
            Route::patch('park/{id}', [AdminController::class, 'park'])->name('park');
        });

        Route::prefix('customers')->name('customers-')->group(function () {
            Route::get('list', [CustomerController::class, 'index'])->name('list');
            Route::post('store', [CustomerController::class, 'store'])->name('store');
            Route::get('show/{id}', [CustomerController::class, 'show'])->name('show');
            Route::put('update/{id}', [CustomerController::class, 'update'])->name('update');
            Route::delete('delete/{id}', [CustomerController::class, 'destroy'])->name('delete');
            Route::patch('approve/{id}', [CustomerController::class, 'approve'])->name('approve');
            Route::patch('park/{id}', [CustomerController::class, 'park'])->name('park');
            Route::patch('{id}/reviews', [CustomerController::class, 'reviews'])->name('reviews');
            Route::patch('{id}/orders', [CustomerController::class, 'orders'])->name('orders');
        });

        Route::prefix('restaurants')->name('restaurants-')->middleware('auth:admin')->group(function () {
            Route::get('list', [RestaurantController::class, 'index'])->name('list');
            Route::get('store', [RestaurantController::class, 'store'])->name('store');
            Route::get('show/{id}', [RestaurantController::class, 'show'])->name('show');
            Route::get('update/{id}', [RestaurantController::class, 'update'])->name('update');
            Route::get('delete/{id}', [RestaurantController::class, 'destroy'])->name('delete');
            Route::get('approve/{id}', [RestaurantController::class, 'approve'])->name('approve');
            Route::get('park/{id}', [RestaurantController::class, 'park'])->name('park');
            Route::patch('{id}/reviews', [RestaurantController::class, 'reviews'])->name('reviews');
            Route::patch('{id}/orders', [RestaurantController::class, 'orders'])->name('orders');
        });

        Route::prefix('deliveries')->name('deliveries-')->middleware('auth:admin')->group(function () {
            Route::get('list', [DeliveryController::class, 'index'])->name('list');
            Route::get('store', [DeliveryController::class, 'store'])->name('store');
            Route::get('show/{id}', [DeliveryController::class, 'show'])->name('show');
            Route::get('update/{id}', [DeliveryController::class, 'update'])->name('update');
            Route::get('delete/{id}', [DeliveryController::class, 'destroy'])->name('delete');
            Route::get('approve/{id}', [DeliveryController::class, 'approve'])->name('approve');
            Route::get('park/{id}', [DeliveryController::class, 'park'])->name('park');
        });

        Route::prefix('aorders')->name('orders-')->middleware('auth:admin')->group(function () {
            Route::get('list', [OrderController::class, 'index'])->name('list');
            Route::get('store', [OrderController::class, 'store'])->name('store');
            Route::get('show/{id}', [OrderController::class, 'show'])->name('show');
            Route::get('update/{id}', [OrderController::class, 'update'])->name('update');
            Route::get('delete/{id}', [OrderController::class, 'destroy'])->name('delete');
            Route::get('approve/{id}', [OrderController::class, 'approve'])->name('approve');
            Route::get('park/{id}', [OrderController::class, 'park'])->name('park');
        });

        Route::prefix('reviews')->name('reviews-')->middleware('auth:admin')->group(function () {
            Route::get('list', [ReviewController::class, 'index'])->name('list');
            Route::get('store', [ReviewController::class, 'store'])->name('store');
            Route::get('show/{id}', [ReviewController::class, 'show'])->name('show');
            Route::get('update/{id}', [ReviewController::class, 'update'])->name('update');
            Route::get('delete/{id}', [ReviewController::class, 'destroy'])->name('delete');
            Route::get('approve/{id}', [ReviewController::class, 'approve'])->name('approve');
            Route::get('park/{id}', [ReviewController::class, 'park'])->name('park');
        });
    });

    // أي محاولة دخول لمسار غير معرف داخل /admin سيتم تحويله لصفحة تسجيل الدخول
    Route::fallback(function () {
        return redirect()->route('admin-login');
    });
});
