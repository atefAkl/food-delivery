<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use App\Models\Category;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DishController extends Controller
{
    /**
     * عرض قائمة الأطباق
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $categoryId = $request->input('category');
        $sortBy = $request->input('sort_by', 'newest');
        
        $dishes = Dish::with(['chef.user', 'reviews'])
            ->where('is_active', true)
            ->when($query, function ($q) use ($query) {
                return $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                return $q->where('category_id', $categoryId);
            });
            
        // الترتيب حسب الاختيار
        switch ($sortBy) {
            case 'price_low':
                $dishes->orderBy('price', 'asc');
                break;
            case 'price_high':
                $dishes->orderBy('price', 'desc');
                break;
            case 'rating':
                $dishes->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
                break;
            case 'newest':
            default:
                $dishes->orderBy('created_at', 'desc');
                break;
        }
        
        $dishes = $dishes->paginate(12);
        $categories = Category::all();
        
        // إذا كان المستخدم مسجل الدخول، قم بتحميل المفضلة
        $favorites = [];
        if (Auth::check() && Auth::user()->type === 'customer') {
            $favorites = Favorite::where('customer_id', Auth::user()->customer->id)
                ->pluck('dish_id')
                ->toArray();
        }
        
        return view('frontend.dishes.index', compact('dishes', 'categories', 'query', 'categoryId', 'sortBy', 'favorites'));
    }
    
    /**
     * عرض تفاصيل طبق معين
     */
    public function show(Dish $dish)
    {
        // التحقق من أن الطبق نشط
        if (!$dish->is_active) {
            abort(404);
        }
        
        $dish->load(['chef.user', 'reviews.customer.user', 'category']);
        
        // حساب متوسط التقييم
        $averageRating = $dish->reviews->avg('rating') ?: 0;
        
        // الأطباق المشابهة
        $similarDishes = Dish::where('category_id', $dish->category_id)
            ->where('id', '!=', $dish->id)
            ->where('is_active', true)
            ->take(4)
            ->get();
            
        // التحقق مما إذا كان الطبق في المفضلة
        $isFavorite = false;
        if (Auth::check() && Auth::user()->type === 'customer') {
            $isFavorite = Favorite::where('customer_id', Auth::user()->customer->id)
                ->where('dish_id', $dish->id)
                ->exists();
        }
        
        // التحقق مما إذا كان المستخدم قد قام بتقييم الطبق من قبل
        $userReview = null;
        if (Auth::check() && Auth::user()->type === 'customer') {
            $userReview = $dish->reviews()
                ->where('customer_id', Auth::user()->customer->id)
                ->first();
        }
        
        return view('frontend.dishes.show', compact(
            'dish', 
            'averageRating', 
            'similarDishes', 
            'isFavorite', 
            'userReview'
        ));
    }
}
