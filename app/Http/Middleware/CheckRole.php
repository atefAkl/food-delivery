<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('frontend.login');
        }
        
        // استخدام hasRole من Spatie Laravel Permission
        if (!$request->user()->hasRole($role)) {
            // يمكننا إما إظهار رسالة خطأ أو إعادة التوجيه إلى الصفحة الرئيسية
            return redirect()->route('frontend.home')
                ->with('error', 'غير مصرح لك بالوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
}
