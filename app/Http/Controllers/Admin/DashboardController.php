<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // التحقق من نوع المستخدم هنا مباشرة
        $user = Auth::guard('admin')->user();
        
        if (!$user || $user->type !== 'admin') {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'ليس لديك صلاحيات كافية للوصول إلى لوحة التحكم']);
        }
        
        return view('admin.dashboard');
    }
}
