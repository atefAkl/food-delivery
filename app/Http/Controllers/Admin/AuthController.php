<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $user = User::where('email', $credentials['email'])->first();

        // التحقق من المصادقة والصلاحيات
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $user = Auth::guard('admin')->user();

            // التحقق من أن المستخدم لديه نوع إداري
            if ($user->type === 'admin') {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            // إذا لم يكن لديه دور إداري، قم بتسجيل خروجه
            Auth::guard('admin')->logout();
            return back()->withErrors([
                'email' => 'ليس لديك صلاحيات كافية للوصول إلى لوحة التحكم',
            ])->withInput();
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
