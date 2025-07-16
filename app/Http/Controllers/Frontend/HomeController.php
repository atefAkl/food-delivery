<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use App\Models\Chef;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * عرض الصفحة الرئيسية
     */
    public function index()
    {
        // الحصول على الأطباق المميزة
        $featuredDishes = Dish::with(['chef.user', 'reviews'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // الحصول على الشيفات المميزين
        $featuredChefs = Chef::with(['user', 'dishes'])
            ->where('is_verified', true)
            ->take(4)
            ->get();

        // الحصول على الفئات
        $categories = Category::all();

        return view('frontend.home', compact('featuredDishes', 'featuredChefs', 'categories'));
    }

    /**
     * عرض صفحة من نحن
     */
    public function about()
    {
        return view('frontend.about.index');
    }

    /**
     * عرض صفحة اتصل بنا
     */
    public function contact()
    {
        return view('frontend.contact.index');
    }

    /**
     * معالجة نموذج الاتصال
     */
    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // هنا يمكن إضافة كود لإرسال البريد الإلكتروني أو حفظ الرسالة في قاعدة البيانات

        return back()->with('success', 'تم إرسال رسالتك بنجاح. سنتواصل معك قريبًا.');
    }

    /**
     * البحث عن الأطباق
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $category = $request->input('category');

        $dishes = Dish::with(['chef.user', 'reviews'])
            ->where('is_active', true)
            ->when($query, function ($q) use ($query) {
                return $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->when($category, function ($q) use ($category) {
                return $q->where('category_id', $category);
            })
            ->paginate(12);

        $categories = Category::all();

        return view('frontend.search', compact('dishes', 'categories', 'query', 'category'));
    }

    /**
     * عرض قائمة الشيفات
     */
    public function chefs(Request $request)
    {
        $search = $request->input('search');
        $specialityFilter = $request->input('speciality');
        $sort = $request->input('sort', 'rating');

        $chefsQuery = Chef::with(['user', 'dishes', 'reviews'])
            ->where('is_verified', true);

        // تطبيق البحث إذا تم تقديمه
        if ($search) {
            $chefsQuery->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
        }

        // تطبيق فلتر التخصص إذا تم تقديمه
        if ($specialityFilter) {
            $chefsQuery->where('speciality', $specialityFilter);
        }

        // تطبيق الترتيب
        switch ($sort) {
            case 'rating':
                $chefsQuery->orderBy('average_rating', 'desc');
                break;
            case 'dishes':
                $chefsQuery->withCount('dishes')->orderBy('dishes_count', 'desc');
                break;
            case 'orders':
                $chefsQuery->withCount('orders')->orderBy('orders_count', 'desc');
                break;
            case 'newest':
                $chefsQuery->orderBy('created_at', 'desc');
                break;
            default:
                $chefsQuery->orderBy('average_rating', 'desc');
        }

        // إضافة الحسابات والعلاقات اللازمة
        $chefs = $chefsQuery
            ->withCount('dishes')
            ->withCount('orders')
            ->withCount('reviews as ratings_count')
            ->withAvg('reviews as average_rating', 'rating')
            ->paginate(12);

        // الحصول على التخصصات المتاحة
        $specialities = Chef::where('is_verified', true)
            ->whereNotNull('speciality')
            ->distinct()
            ->pluck('speciality')
            ->toArray();

        return view('frontend.chefs.index', compact('chefs', 'specialities', 'search', 'specialityFilter', 'sort'));
    }

    /**
     * عرض ملف تعريف الشيف
     */
    public function chefProfile(Chef $chef)
    {
        $chef->load(['user', 'dishes', 'reviews']);

        $dishes = $chef->dishes()
            ->where('is_active', true)
            ->paginate(8);

        return view('frontend.chef_profile', compact('chef', 'dishes'));
    }
}
