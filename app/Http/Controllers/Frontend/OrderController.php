<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * عرض صفحة إتمام الطلب
     */
    public function checkout()
    {
        $customer = Auth::user()->customer;
        $cart = $customer->cart;
        
        // التحقق من وجود عناصر في سلة التسوق
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('frontend.cart')
                ->with('error', 'لا يمكن إتمام الطلب. سلة التسوق فارغة.');
        }
        
        // تحميل عناوين العميل
        $addresses = $customer->addresses;
        
        // التحقق من وجود عنوان واحد على الأقل
        if ($addresses->isEmpty()) {
            return redirect()->route('frontend.addresses')
                ->with('error', 'يرجى إضافة عنوان للتوصيل قبل إتمام الطلب.');
        }
        
        // تحميل تفاصيل العناصر في السلة
        $cart->load('cartItems.dish.chef.user');
        
        return view('frontend.orders.checkout', compact('cart', 'addresses'));
    }
    
    /**
     * إنشاء طلب جديد
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cash,credit_card',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $customer = Auth::user()->customer;
        $cart = $customer->cart;
        
        // التحقق من وجود عناصر في سلة التسوق
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('frontend.cart')
                ->with('error', 'لا يمكن إتمام الطلب. سلة التسوق فارغة.');
        }
        
        // التحقق من أن العنوان ينتمي للعميل الحالي
        $address = Address::findOrFail($request->address_id);
        if ($address->customer_id !== $customer->id) {
            abort(403);
        }
        
        try {
            DB::beginTransaction();
            
            // إنشاء الطلب
            $order = new Order([
                'customer_id' => $customer->id,
                'address_id' => $address->id,
                'total_amount' => $cart->total_amount,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'notes' => $request->notes,
            ]);
            
            $order->save();
            
            // إنشاء عناصر الطلب
            foreach ($cart->cartItems as $cartItem) {
                $orderItem = new OrderItem([
                    'order_id' => $order->id,
                    'dish_id' => $cartItem->dish_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->dish->price,
                    'subtotal' => $cartItem->subtotal,
                ]);
                
                $order->orderItems()->save($orderItem);
            }
            
            // إنشاء سجل حالة الطلب
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'notes' => 'تم إنشاء الطلب',
            ]);
            
            // حذف عناصر سلة التسوق
            $cart->cartItems()->delete();
            
            // تحديث إجمالي سلة التسوق
            $cart->update(['total_amount' => 0]);
            
            DB::commit();
            
            return redirect()->route('frontend.customer.orders.show', $order)
                ->with('success', 'تم إنشاء الطلب بنجاح. رقم الطلب: ' . $order->id);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('frontend.checkout')
                ->with('error', 'حدث خطأ أثناء إنشاء الطلب. يرجى المحاولة مرة أخرى.');
        }
    }
    
    /**
     * عرض طلبات العميل
     */
    public function customerOrders()
    {
        $customer = Auth::user()->customer;
        
        $orders = Order::where('customer_id', $customer->id)
            ->with(['orderItems.dish'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('frontend.orders.index', compact('orders'));
    }
    
    /**
     * عرض تفاصيل طلب للعميل
     */
    public function customerOrderDetails(Order $order)
    {
        // التحقق من أن الطلب ينتمي للعميل الحالي
        if ($order->customer_id !== Auth::user()->customer->id) {
            abort(403);
        }
        
        $order->load(['orderItems.dish.chef.user', 'address', 'statusHistory']);
        
        return view('frontend.orders.show', compact('order'));
    }
}
