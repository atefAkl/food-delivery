<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ChefController;
use App\Http\Controllers\Frontend\CustomerController;
use App\Http\Controllers\Frontend\DishController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\ProfileController;

/*
|--------------------------------------------------------------------------
| واجهة المستخدم الأمامية للشيفات والعملاء
|--------------------------------------------------------------------------
|
| هنا يتم تعريف جميع مسارات واجهة المستخدم الأمامية للشيفات والعملاء
| بحيث يمكنهم التسجيل والدخول واستخدام التطبيق من خلال الويب
|
*/

// الصفحة الرئيسية والمسارات العامة
Route::get('/', [HomeController::class, 'index'])->name('frontend.home');
Route::get('/about', [HomeController::class, 'about'])->name('frontend.about');
Route::get('/contact', [HomeController::class, 'contact'])->name('frontend.contact');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('frontend.contact.send');

// مسارات المستخدمين غير المسجلين
Route::middleware('guest')->name('frontend.')->group(function () {
    // مسارات تسجيل الدخول
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // مسارات التسجيل
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    // مسارات استعادة كلمة المرور
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// مسارات المستخدمين المسجلين
Route::middleware('auth')->name('frontend.')->group(function () {
    // مسار تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // الملف الشخصي للمستخدم
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // مسارات مشتركة بين الشيفات والعملاء
    Route::get('/dishes', [DishController::class, 'index'])->name('dishes');
    Route::get('/dishes/{dish}', [DishController::class, 'show'])->name('dishes.show');

    // مسارات خاصة بالعملاء
    Route::middleware('role:customer')->group(function () {
        Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
        Route::get('/cart', [CustomerController::class, 'cart'])->name('cart');
        Route::post('/cart/add', [CustomerController::class, 'addToCart'])->name('frontend.cart.add');
        Route::put('/cart/update', [CustomerController::class, 'updateCart'])->name('frontend.cart.update');
        Route::delete('/cart/remove/{item}', [CustomerController::class, 'removeFromCart'])->name('frontend.cart.remove');

        Route::get('/checkout', [OrderController::class, 'checkout'])->name('frontend.checkout');
        Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('frontend.order.place');

        Route::get('/orders', [OrderController::class, 'customerOrders'])->name('frontend.customer.orders');
        Route::get('/orders/{order}', [OrderController::class, 'customerOrderDetails'])->name('frontend.customer.orders.show');

        Route::post('/dishes/{dish}/favorite', [CustomerController::class, 'toggleFavorite'])->name('frontend.dishes.favorite');
        Route::get('/favorites', [CustomerController::class, 'favorites'])->name('frontend.favorites');

        Route::post('/dishes/{dish}/review', [CustomerController::class, 'addReview'])->name('frontend.dishes.review');

        Route::get('/addresses', [CustomerController::class, 'addresses'])->name('frontend.addresses');
        Route::post('/addresses', [CustomerController::class, 'storeAddress'])->name('frontend.addresses.store');
        Route::put('/addresses/{address}', [CustomerController::class, 'updateAddress'])->name('frontend.addresses.update');
        Route::delete('/addresses/{address}', [CustomerController::class, 'deleteAddress'])->name('frontend.addresses.delete');
        Route::patch('/addresses/{address}/default', [CustomerController::class, 'setDefaultAddress'])->name('frontend.addresses.default');
    });

    // مسارات خاصة بالشيفات
    Route::middleware('role:chef')->group(function () {
        Route::get('/chef/dashboard', [ChefController::class, 'dashboard'])->name('frontend.chef.dashboard');

        Route::get('/chef/dishes', [ChefController::class, 'dishes'])->name('frontend.chef.dishes');
        Route::get('/chef/dishes/create', [ChefController::class, 'createDish'])->name('frontend.chef.dishes.create');
        Route::post('/chef/dishes', [ChefController::class, 'storeDish'])->name('frontend.chef.dishes.store');
        Route::get('/chef/dishes/{dish}/edit', [ChefController::class, 'editDish'])->name('frontend.chef.dishes.edit');
        Route::put('/chef/dishes/{dish}', [ChefController::class, 'updateDish'])->name('frontend.chef.dishes.update');
        Route::delete('/chef/dishes/{dish}', [ChefController::class, 'deleteDish'])->name('frontend.chef.dishes.delete');

        Route::get('/chef/orders', [ChefController::class, 'orders'])->name('frontend.chef.orders');
        Route::get('/chef/orders/{order}', [ChefController::class, 'orderDetails'])->name('frontend.chef.orders.show');
        Route::patch('/chef/orders/{order}/status', [ChefController::class, 'updateOrderStatus'])->name('frontend.chef.orders.status');

        Route::get('/chef/reviews', [ChefController::class, 'reviews'])->name('frontend.chef.reviews');

        Route::get('/chef/earnings', [ChefController::class, 'earnings'])->name('frontend.chef.earnings');
        Route::get('/chef/payouts', [ChefController::class, 'payouts'])->name('frontend.chef.payouts');
        Route::post('/chef/payouts/request', [ChefController::class, 'requestPayout'])->name('frontend.chef.payouts.request');
    });
});

// مسارات عرض الأطباق والبحث (متاحة للجميع)
Route::get('/search', [HomeController::class, 'search'])->name('frontend.search');
Route::get('/chefs', [HomeController::class, 'chefs'])->name('frontend.chefs');
Route::get('/dish/show/{dish}', [HomeController::class, 'dishShow'])->name('frontend.dish.show');
Route::get('/chefs/{chef}', [HomeController::class, 'chefProfile'])->name('frontend.chefs.profile');
