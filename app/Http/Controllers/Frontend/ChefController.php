<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use App\Models\Chef;
use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChefController extends Controller
{
    /**
     * عرض لوحة تحكم الشيف
     */
    public function dashboard()
    {
        $chef = User::find(Auth::id());
        //return $chef->load('chef.dishes');
        // إحصائيات للشيف
        $totalDishes = $chef->chef->dishes()->count();
        $totalOrders = $chef->chef->orders()->count();
        $totalEarnings = $chef->chef->orders()->sum('total');
        $pendingOrders = $chef->chef->orders()->whereIn('status', ['pending', 'processing'])->count();

        $topDishes = $chef->chef->dishes()
            ->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get();

        // آخر الطلبات
        $recentOrders = $chef->chef->orders()
            ->with('customer.user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // آخر التقييمات
        $recentReviews = Review::whereHas('dish', function ($query) use ($chef) {
            $query->where('chef_id', $chef->id);
        })
            ->with(['customer.user', 'dish'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('frontend.chef.dashboard', compact(
            'chef',
            'topDishes',
            'totalDishes',
            'totalOrders',
            'totalEarnings',
            'pendingOrders',
            'recentOrders',
            'recentReviews'
        ));
    }

    /**
     * عرض قائمة أطباق الشيف
     */
    public function dishes()
    {
        $chef = Auth::user()->chef;
        $dishes = $chef->dishes()->paginate(10);

        return view('frontend.chef.dishes.index', compact('dishes'));
    }

    /**
     * عرض نموذج إنشاء طبق جديد
     */
    public function createDish()
    {
        $categories = Category::all();
        return view('frontend.chef.dishes.create', compact('categories'));
    }

    /**
     * حفظ طبق جديد
     */
    public function storeDish(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'preparation_time' => 'required|integer|min:1',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $chef = Auth::user()->chef;

        // معالجة الصورة
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('dishes', 'public');
        }

        // إنشاء الطبق
        $dish = new Dish([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'preparation_time' => $request->preparation_time,
            'image' => $imagePath,
            'is_active' => true,
        ]);

        $chef->dishes()->save($dish);

        return redirect()->route('frontend.chef.dishes')
            ->with('success', 'تم إضافة الطبق بنجاح');
    }

    /**
     * عرض نموذج تعديل طبق
     */
    public function editDish(Dish $dish)
    {
        // التحقق من أن الطبق ينتمي للشيف الحالي
        if ($dish->chef_id !== Auth::user()->chef->id) {
            abort(403);
        }

        $categories = Category::all();
        return view('frontend.chef.dishes.edit', compact('dish', 'categories'));
    }

    /**
     * تحديث طبق
     */
    public function updateDish(Request $request, Dish $dish)
    {
        // التحقق من أن الطبق ينتمي للشيف الحالي
        if ($dish->chef_id !== Auth::user()->chef->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'preparation_time' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // معالجة الصورة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا وجدت
            if ($dish->image) {
                Storage::disk('public')->delete($dish->image);
            }

            $imagePath = $request->file('image')->store('dishes', 'public');
            $dish->image = $imagePath;
        }

        // تحديث الطبق
        $dish->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'preparation_time' => $request->preparation_time,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('frontend.chef.dishes')
            ->with('success', 'تم تحديث الطبق بنجاح');
    }

    /**
     * حذف طبق
     */
    public function deleteDish(Dish $dish)
    {
        // التحقق من أن الطبق ينتمي للشيف الحالي
        if ($dish->chef_id !== Auth::user()->chef->id) {
            abort(403);
        }

        // حذف الصورة إذا وجدت
        if ($dish->image) {
            Storage::disk('public')->delete($dish->image);
        }

        $dish->delete();

        return redirect()->route('frontend.chef.dishes')
            ->with('success', 'تم حذف الطبق بنجاح');
    }

    /**
     * عرض طلبات الشيف
     */
    public function orders()
    {
        $chef = Auth::user()->chef;
        $orders = $chef->orders()->with('customer.user')->latest()->paginate(10);

        return view('frontend.chef.orders.index', compact('orders'));
    }

    /**
     * عرض تفاصيل طلب
     */
    public function orderDetails(Order $order)
    {
        $chef = Auth::user()->chef;

        // التحقق من أن الطلب يحتوي على أطباق من هذا الشيف
        $hasChefDishes = $order->orderItems()->whereHas('dish', function ($query) use ($chef) {
            $query->where('chef_id', $chef->id);
        })->exists();

        if (!$hasChefDishes) {
            abort(403);
        }

        $order->load(['customer.user', 'orderItems.dish']);

        return view('frontend.chef.orders.show', compact('order'));
    }

    /**
     * تحديث حالة الطلب
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $chef = Auth::user()->chef;

        // التحقق من أن الطلب يحتوي على أطباق من هذا الشيف
        $hasChefDishes = $order->orderItems()->whereHas('dish', function ($query) use ($chef) {
            $query->where('chef_id', $chef->id);
        })->exists();

        if (!$hasChefDishes) {
            abort(403);
        }

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->route('frontend.chef.orders.show', $order)
            ->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    /**
     * عرض تقييمات الشيف
     */
    public function reviews()
    {
        $chef = Auth::user()->chef;

        $reviews = Review::whereHas('dish', function ($query) use ($chef) {
            $query->where('chef_id', $chef->id);
        })
            ->with(['customer.user', 'dish'])
            ->latest()
            ->paginate(10);

        return view('frontend.chef.reviews', compact('reviews'));
    }

    /**
     * عرض أرباح الشيف
     */
    public function earnings()
    {
        $chef = Auth::user()->chef;

        // إجمالي الأرباح
        $totalEarnings = $chef->orders()->where('status', 'completed')->sum('total_amount');

        // أرباح هذا الشهر
        $monthlyEarnings = $chef->orders()
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');

        // أرباح هذا الأسبوع
        $weeklyEarnings = $chef->orders()
            ->where('status', 'completed')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total_amount');

        // إحصائيات الأرباح حسب الشهر (للرسم البياني)
        $monthlyStats = $chef->orders()
            ->where('status', 'completed')
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->get();

        return view('frontend.chef.earnings', compact(
            'totalEarnings',
            'monthlyEarnings',
            'weeklyEarnings',
            'monthlyStats'
        ));
    }

    /**
     * عرض المدفوعات
     */
    public function payouts()
    {
        $chef = Auth::user()->chef;

        // هنا يمكن إضافة كود لعرض سجل المدفوعات السابقة

        return view('frontend.chef.payouts', [
            'balance' => $chef->balance,
        ]);
    }

    /**
     * طلب سحب الأرباح
     */
    public function requestPayout(Request $request)
    {
        $chef = Auth::user()->chef;

        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $chef->balance,
        ]);

        // هنا يمكن إضافة كود لمعالجة طلب سحب الأرباح

        return redirect()->route('frontend.chef.payouts')
            ->with('success', 'تم إرسال طلب سحب الأرباح بنجاح. سيتم معالجته خلال 1-3 أيام عمل.');
    }
}
