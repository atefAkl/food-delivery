<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Chef;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * عرض نموذج تسجيل الدخول
     */
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    /**
     * معالجة طلب تسجيل الدخول
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // توجيه المستخدم حسب نوعه
            $user = User::find(Auth::id());
            $user->assignRole($user->type);
            if ($user->type === 'customer') {
                return redirect('/customer/dashboard');
            } elseif ($user->type === 'chef') {
                return redirect('/chef/dashboard');
            } elseif ($user->type === 'admin') {
                return redirect()->route('admin-dashboard');
            }



            return redirect()->intended(route('frontend.home'));
        }

        return back()->withErrors([
            'email' => 'بيانات الاعتماد المقدمة غير صحيحة.',
        ])->withInput($request->except('password'));
    }

    /**
     * عرض نموذج التسجيل
     */
    public function showRegisterForm()
    {
        return view('frontend.auth.register');
    }

    /**
     * معالجة طلب التسجيل
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => ['required', 'confirmed'],
            'type' => 'required|in:customer,chef',
        ]);

        // إنشاء المستخدم
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'type' => $request->type, // سيتم تعيين الدور تلقائيًا عبر setTypeAttribute
        ]);

        // تأكد من تعيين الدور بشكل صريح (لضمان عمل الصلاحيات)
        $user->assignRole($request->type);

        // إنشاء ملف تعريف حسب نوع المستخدم
        if ($request->type === 'customer') {
            Customer::create([
                'id' => $user->id,
            ]);
        } elseif ($request->type === 'chef') {
            Chef::create([
                'id' => $user->id,
                'is_verified' => false,
            ]);
        }

        // تسجيل الدخول تلقائيًا بعد التسجيل
        Auth::login($user);

        // توجيه المستخدم حسب نوعه
        if ($request->type === 'customer') {
            return redirect()->route('frontend.customer.dashboard');
        } elseif ($request->type === 'chef') {
            return redirect()->route('frontend.chef.dashboard');
        }

        return redirect()->route('frontend.home');
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('frontend.home');
    }

    /**
     * عرض نموذج نسيان كلمة المرور
     */
    public function showForgotPasswordForm()
    {
        return view('frontend.auth.forgot-password');
    }

    /**
     * إرسال رابط إعادة تعيين كلمة المرور
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * عرض نموذج إعادة تعيين كلمة المرور
     */
    public function showResetPasswordForm(Request $request, $token)
    {
        return view('frontend.auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * إعادة تعيين كلمة المرور
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('frontend.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
