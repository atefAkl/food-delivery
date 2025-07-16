<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('admin')->check()) {
            return redirect()->route('admin.login');
        }
        
        $user = auth('admin')->user();
        
        if ($user->type !== 'admin') {
            auth('admin')->logout();
            return redirect()->route('admin.login')->withErrors([
                'email' => 'ليس لديك صلاحيات كافية للوصول إلى لوحة التحكم'
            ]);
        }
        
        return $next($request);
    }
}
