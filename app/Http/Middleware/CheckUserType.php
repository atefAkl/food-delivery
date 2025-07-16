<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckUserType
{
    /**
     * فحص نوع المستخدم قبل السماح له بالوصول إلى المسار
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $type
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $type)
    {
        if (!Auth::check()) {
            return redirect()->route('frontend.login');
        }
        
        $user = Auth::user();
        
        // التحقق من النوع مباشرة كطريقة أساسية
        if ($user->type !== $type) {
            abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة.');
        }
        
        // التأكد من أن المستخدم لديه الدور المناسب في نظام Spatie
        // وإذا لم يكن لديه، قم بتعيينه
        try {
            // استخدام طرق Spatie داخل try-catch لتجنب الأخطاء في حالة عدم تعريفها
            $hasRole = method_exists($user, 'hasRole') ? $user->hasRole($type) : false;
            if (!$hasRole) {
                if (method_exists($user, 'assignRole')) {
                    $user->assignRole($type);
                }
            }
        } catch (\Exception $e) {
            // تجاهل الأخطاء - سنعتمد على نوع المستخدم في هذه الحالة
            // ويمكن تسجيل الخطأ هنا إذا لزم الأمر
        }
        
        return $next($request);
    }
}
