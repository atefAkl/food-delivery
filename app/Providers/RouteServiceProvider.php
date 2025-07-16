<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';
    public const ADMIN_DASHBOARD = '/admin/dashboard';
    public const CUSTOMER_DASHBOARD = '/customer/dashboard';
    public const CHEF_DASHBOARD = '/chef/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // ملاحظة: تسجيل web.php فقط
        // وهو يضم بدوره ملفات admin.php و frontend.php لتجنب التكرار
        $this->routes(function () {
            // الطريقة الصحيحة لتسجيل المسارات - كل نوع على حدة
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
                
            // تم التعليق على هذه المسارات لأنها مضمنة بالفعل في web.php
            // وتسجيلها مرة أخرى هنا يسبب تسجيلها مرتين
            // Route::middleware('web')
            //    ->prefix('admin')
            //    ->group(base_path('routes/admin.php'));
            // Route::middleware('web')
            //    ->group(base_path('routes/chef.php'));
        });
    }
}
