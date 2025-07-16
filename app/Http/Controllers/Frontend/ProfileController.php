<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * عرض الملف الشخصي للمستخدم
     */
    public function show()
    {
        $user = Auth::user();
        
        // تحميل البيانات الإضافية حسب نوع المستخدم
        if ($user->type === 'customer') {
            $user->customer;
            $user->customer->addresses;
        } elseif ($user->type === 'chef') {
            $user->chef;
        }
        
        return view('frontend.profile.show', compact('user'));
    }
    
    /**
     * تحديث الملف الشخصي للمستخدم
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // معالجة الصورة الشخصية
        if ($request->hasFile('profile_image')) {
            // حذف الصورة القديمة إذا وجدت
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $imagePath;
        }
        
        // تحديث بيانات المستخدم الأساسية
        $user = \App\Models\User::find($user->id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->bio = $request->bio;
        $user->save();
        
        // تحديث البيانات الإضافية حسب نوع المستخدم
        if ($user->type === 'chef' && $user->chef) {
            $request->validate([
                'description' => 'nullable|string|max:1000',
                'location' => 'nullable|string|max:255',
            ]);
            
            $user->chef->update([
                'description' => $request->description,
                'location' => $request->location,
            ]);
        }
        
        return redirect()->route('frontend.profile')
            ->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
    
    /**
     * تحديث كلمة المرور
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $user = Auth::user();
        
        // التحقق من كلمة المرور الحالية
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }
        
        // تحديث كلمة المرور
        $user = \App\Models\User::find($user->id);
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->route('frontend.profile')
            ->with('success', 'تم تحديث كلمة المرور بنجاح');
    }
}
