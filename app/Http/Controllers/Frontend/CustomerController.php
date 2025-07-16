<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Dish;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * عرض لوحة تحكم العميل
     */
    public function dashboard()
    {
        $customer = Auth::user()->customer;
        
        // آخر الطلبات
        $recentOrders = Order::where('customer_id', $customer->id)
            ->with(['orderItems.dish'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // الأطباق المفضلة
        $favorites = Favorite::where('customer_id', $customer->id)
            ->with(['dish.chef.user'])
            ->take(4)
            ->get();
            
        // إحصائيات
        $totalOrders = Order::where('customer_id', $customer->id)->count();
        $pendingOrders = Order::where('customer_id', $customer->id)
            ->whereIn('status', ['pending', 'processing'])
            ->count();
            
        return view('frontend.customer.dashboard', compact(
            'customer',
            'recentOrders',
            'favorites',
            'totalOrders',
            'pendingOrders'
        ));
    }
    
    /**
     * عرض سلة التسوق
     */
    public function cart()
    {
        $customer = Auth::user()->customer;
        $cart = $customer->cart;
        
        if (!$cart) {
            $cart = Cart::create([
                'customer_id' => $customer->id,
                'total_amount' => 0,
            ]);
        }
        
        $cart->load('cartItems.dish.chef.user');
        
        return view('frontend.customer.cart', compact('cart'));
    }
    
    /**
     * إضافة طبق إلى سلة التسوق
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'dish_id' => 'required|exists:dishes,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $customer = Auth::user()->customer;
        $dish = Dish::findOrFail($request->dish_id);
        
        // التحقق من وجود سلة تسوق للعميل
        $cart = $customer->cart;
        if (!$cart) {
            $cart = Cart::create([
                'customer_id' => $customer->id,
                'total_amount' => 0,
            ]);
        }
        
        // التحقق من وجود الطبق في السلة
        $cartItem = $cart->cartItems()->where('dish_id', $dish->id)->first();
        
        if ($cartItem) {
            // تحديث الكمية إذا كان الطبق موجودًا بالفعل
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity,
                'subtotal' => ($cartItem->quantity + $request->quantity) * $dish->price,
            ]);
        } else {
            // إضافة الطبق إلى السلة
            $cartItem = new CartItem([
                'dish_id' => $dish->id,
                'quantity' => $request->quantity,
                'subtotal' => $request->quantity * $dish->price,
            ]);
            
            $cart->cartItems()->save($cartItem);
        }
        
        // تحديث إجمالي السلة
        $cart->update([
            'total_amount' => $cart->cartItems()->sum('subtotal'),
        ]);
        
        return redirect()->route('frontend.cart')
            ->with('success', 'تمت إضافة الطبق إلى سلة التسوق بنجاح');
    }
    
    /**
     * تحديث سلة التسوق
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:cart_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        
        $customer = Auth::user()->customer;
        $cart = $customer->cart;
        
        foreach ($request->items as $item) {
            $cartItem = CartItem::findOrFail($item['id']);
            
            // التحقق من أن العنصر ينتمي لسلة العميل الحالي
            if ($cartItem->cart_id !== $cart->id) {
                continue;
            }
            
            $dish = Dish::findOrFail($cartItem->dish_id);
            
            $cartItem->update([
                'quantity' => $item['quantity'],
                'subtotal' => $item['quantity'] * $dish->price,
            ]);
        }
        
        // تحديث إجمالي السلة
        $cart->update([
            'total_amount' => $cart->cartItems()->sum('subtotal'),
        ]);
        
        return redirect()->route('frontend.cart')
            ->with('success', 'تم تحديث سلة التسوق بنجاح');
    }
    
    /**
     * إزالة طبق من سلة التسوق
     */
    public function removeFromCart($itemId)
    {
        $customer = Auth::user()->customer;
        $cart = $customer->cart;
        
        $cartItem = CartItem::findOrFail($itemId);
        
        // التحقق من أن العنصر ينتمي لسلة العميل الحالي
        if ($cartItem->cart_id !== $cart->id) {
            abort(403);
        }
        
        $cartItem->delete();
        
        // تحديث إجمالي السلة
        $cart->update([
            'total_amount' => $cart->cartItems()->sum('subtotal'),
        ]);
        
        return redirect()->route('frontend.cart')
            ->with('success', 'تمت إزالة الطبق من سلة التسوق بنجاح');
    }
    
    /**
     * إضافة/إزالة طبق من المفضلة
     */
    public function toggleFavorite(Dish $dish)
    {
        $customer = Auth::user()->customer;
        
        $favorite = Favorite::where('customer_id', $customer->id)
            ->where('dish_id', $dish->id)
            ->first();
            
        if ($favorite) {
            $favorite->delete();
            $message = 'تمت إزالة الطبق من المفضلة بنجاح';
        } else {
            Favorite::create([
                'customer_id' => $customer->id,
                'dish_id' => $dish->id,
            ]);
            $message = 'تمت إضافة الطبق إلى المفضلة بنجاح';
        }
        
        return back()->with('success', $message);
    }
    
    /**
     * عرض الأطباق المفضلة
     */
    public function favorites()
    {
        $customer = Auth::user()->customer;
        
        $favorites = Favorite::where('customer_id', $customer->id)
            ->with(['dish.chef.user', 'dish.reviews'])
            ->paginate(12);
            
        return view('frontend.customer.favorites', compact('favorites'));
    }
    
    /**
     * إضافة تقييم لطبق
     */
    public function addReview(Request $request, Dish $dish)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);
        
        $customer = Auth::user()->customer;
        
        // التحقق من أن العميل قد طلب هذا الطبق من قبل
        $hasOrdered = Order::where('customer_id', $customer->id)
            ->whereHas('orderItems', function ($query) use ($dish) {
                $query->where('dish_id', $dish->id);
            })
            ->where('status', 'completed')
            ->exists();
            
        if (!$hasOrdered) {
            return back()->withErrors(['message' => 'يجب أن تكون قد طلبت هذا الطبق من قبل لتتمكن من تقييمه']);
        }
        
        // التحقق من وجود تقييم سابق
        $existingReview = Review::where('customer_id', $customer->id)
            ->where('dish_id', $dish->id)
            ->first();
            
        if ($existingReview) {
            $existingReview->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            
            $message = 'تم تحديث تقييمك بنجاح';
        } else {
            Review::create([
                'customer_id' => $customer->id,
                'dish_id' => $dish->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            
            $message = 'تم إضافة تقييمك بنجاح';
        }
        
        return back()->with('success', $message);
    }
    
    /**
     * عرض عناوين العميل
     */
    public function addresses()
    {
        $customer = Auth::user()->customer;
        $addresses = $customer->addresses;
        
        return view('frontend.customer.addresses.index', compact('addresses'));
    }
    
    /**
     * إضافة عنوان جديد
     */
    public function storeAddress(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);
        
        $customer = Auth::user()->customer;
        
        // إذا كان العنوان الجديد هو الافتراضي، قم بإلغاء تعيين العناوين الأخرى كافتراضية
        if ($request->is_default) {
            $customer->addresses()->update(['is_default' => false]);
        }
        
        // إذا كان هذا أول عنوان، اجعله افتراضيًا
        $isFirstAddress = $customer->addresses()->count() === 0;
        
        $address = new Address([
            'title' => $request->title,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
            'is_default' => $request->is_default || $isFirstAddress,
        ]);
        
        $customer->addresses()->save($address);
        
        return redirect()->route('frontend.addresses')
            ->with('success', 'تمت إضافة العنوان بنجاح');
    }
    
    /**
     * تحديث عنوان
     */
    public function updateAddress(Request $request, Address $address)
    {
        // التحقق من أن العنوان ينتمي للعميل الحالي
        if ($address->customer_id !== Auth::user()->customer->id) {
            abort(403);
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);
        
        $customer = Auth::user()->customer;
        
        // إذا كان العنوان المحدث هو الافتراضي، قم بإلغاء تعيين العناوين الأخرى كافتراضية
        if ($request->is_default) {
            $customer->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }
        
        $address->update([
            'title' => $request->title,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
            'is_default' => $request->is_default,
        ]);
        
        return redirect()->route('frontend.addresses')
            ->with('success', 'تم تحديث العنوان بنجاح');
    }
    
    /**
     * حذف عنوان
     */
    public function deleteAddress(Address $address)
    {
        // التحقق من أن العنوان ينتمي للعميل الحالي
        if ($address->customer_id !== Auth::user()->customer->id) {
            abort(403);
        }
        
        $isDefault = $address->is_default;
        
        $address->delete();
        
        // إذا كان العنوان المحذوف هو الافتراضي، قم بتعيين عنوان آخر كافتراضي
        if ($isDefault) {
            $customer = Auth::user()->customer;
            $newDefaultAddress = $customer->addresses()->first();
            
            if ($newDefaultAddress) {
                $newDefaultAddress->update(['is_default' => true]);
            }
        }
        
        return redirect()->route('frontend.addresses')
            ->with('success', 'تم حذف العنوان بنجاح');
    }
    
    /**
     * تعيين عنوان كافتراضي
     */
    public function setDefaultAddress(Address $address)
    {
        // التحقق من أن العنوان ينتمي للعميل الحالي
        if ($address->customer_id !== Auth::user()->customer->id) {
            abort(403);
        }
        
        $customer = Auth::user()->customer;
        
        // إلغاء تعيين العناوين الأخرى كافتراضية
        $customer->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        
        // تعيين العنوان المحدد كافتراضي
        $address->update(['is_default' => true]);
        
        return redirect()->route('frontend.addresses')
            ->with('success', 'تم تعيين العنوان كافتراضي بنجاح');
    }
}
