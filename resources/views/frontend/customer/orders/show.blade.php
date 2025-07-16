@extends('frontend.layouts.app')

@section('title', 'تفاصيل الطلب')

@section('content')
<div class="row">
    <!-- القائمة الجانبية -->
    <div class="col-lg-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <img src="{{ Auth::user()->profile_image ?? '/images/default-user.jpg' }}" class="rounded-circle mb-3" alt="{{ Auth::user()->name }}" width="100" height="100" style="object-fit: cover;">
                <h4>{{ Auth::user()->name }}</h4>
                <p class="text-muted">
                    <span class="badge bg-primary">عميل</span>
                </p>
                <p class="text-muted">{{ Auth::user()->email }}</p>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('frontend.customer.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.customer.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> لوحة التحكم
                </a>
                <a href="{{ route('frontend.customer.orders') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.customer.orders*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag me-2"></i> طلباتي
                </a>
                <a href="{{ route('frontend.favorites') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.favorites') ? 'active' : '' }}">
                    <i class="fas fa-heart me-2"></i> المفضلة
                </a>
                <a href="{{ route('frontend.customer.addresses') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.customer.addresses*') ? 'active' : '' }}">
                    <i class="fas fa-map-marker-alt me-2"></i> عناويني
                </a>
                <a href="{{ route('frontend.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.profile') ? 'active' : '' }}">
                    <i class="fas fa-user me-2"></i> الملف الشخصي
                </a>
                
                <form action="{{ route('frontend.logout') }}" method="POST" class="mt-3 px-3 pb-3">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- محتوى تفاصيل الطلب -->
    <div class="col-lg-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">تفاصيل الطلب #{{ $order->id }}</h4>
                <a href="{{ route('frontend.customer.orders') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right me-2"></i> العودة للطلبات
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                <!-- حالة الطلب -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0">حالة الطلب:</h5>
                                    </div>
                                    <div>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark fs-6">قيد الانتظار</span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-info fs-6">قيد التحضير</span>
                                                @break
                                            @case('shipping')
                                                <span class="badge bg-primary fs-6">قيد التوصيل</span>
                                                @break
                                            @case('delivered')
                                                <span class="badge bg-success fs-6">تم التوصيل</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger fs-6">ملغي</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary fs-6">{{ $order->status }}</span>
                                        @endswitch
                                    </div>
                                </div>
                                
                                <!-- شريط تقدم الطلب -->
                                @if($order->status != 'cancelled')
                                    <div class="mt-4">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 
                                                @if($order->status == 'pending') 25%
                                                @elseif($order->status == 'processing') 50%
                                                @elseif($order->status == 'shipping') 75%
                                                @elseif($order->status == 'delivered') 100%
                                                @endif
                                            " aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <div class="text-center">
                                                <i class="fas fa-check-circle {{ $order->status != 'cancelled' ? 'text-success' : 'text-muted' }}"></i>
                                                <div class="small">تم الطلب</div>
                                            </div>
                                            <div class="text-center">
                                                <i class="fas fa-utensils {{ in_array($order->status, ['processing', 'shipping', 'delivered']) ? 'text-success' : 'text-muted' }}"></i>
                                                <div class="small">قيد التحضير</div>
                                            </div>
                                            <div class="text-center">
                                                <i class="fas fa-truck {{ in_array($order->status, ['shipping', 'delivered']) ? 'text-success' : 'text-muted' }}"></i>
                                                <div class="small">قيد التوصيل</div>
                                            </div>
                                            <div class="text-center">
                                                <i class="fas fa-home {{ $order->status == 'delivered' ? 'text-success' : 'text-muted' }}"></i>
                                                <div class="small">تم التوصيل</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- الإجراءات المتاحة -->
                                <div class="mt-3 text-center">
                                    @if($order->status === 'pending')
                                        <form action="{{ route('frontend.customer.orders.cancel', $order->id) }}" method="POST" class="d-inline cancel-form">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-times me-2"></i> إلغاء الطلب
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($order->status === 'delivered' && !$order->is_rated)
                                        <a href="{{ route('frontend.customer.orders.rate', $order->id) }}" class="btn btn-warning">
                                            <i class="fas fa-star me-2"></i> تقييم الطلب
                                        </a>
                                    @endif
                                    
                                    @if($order->status === 'delivered' && $order->is_rated)
                                        <span class="badge bg-success fs-6">
                                            <i class="fas fa-check-circle me-2"></i> تم التقييم
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- معلومات الطلب -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">معلومات الطلب</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>رقم الطلب:</span>
                                        <span class="fw-bold">#{{ $order->id }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>تاريخ الطلب:</span>
                                        <span>{{ $order->created_at->format('d/m/Y - h:i A') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>الشيف:</span>
                                        <span>{{ $order->chef->user->name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>طريقة الدفع:</span>
                                        <span>
                                            @if($order->payment_method == 'cash')
                                                <i class="fas fa-money-bill-wave me-1"></i> الدفع عند الاستلام
                                            @elseif($order->payment_method == 'card')
                                                <i class="fas fa-credit-card me-1"></i> بطاقة ائتمانية
                                            @else
                                                {{ $order->payment_method }}
                                            @endif
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>حالة الدفع:</span>
                                        @if($order->payment_status == 'paid')
                                            <span class="badge bg-success">تم الدفع</span>
                                        @else
                                            <span class="badge bg-warning text-dark">غير مدفوع</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- معلومات التوصيل -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">معلومات التوصيل</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>المستلم:</strong> {{ $order->address->recipient_name }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>رقم الهاتف:</strong> {{ $order->address->phone }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>العنوان:</strong>
                                        <address class="mb-0 mt-1">
                                            {{ $order->address->city }}، {{ $order->address->area }}،<br>
                                            شارع {{ $order->address->street }}، مبنى {{ $order->address->building_no }}
                                            @if($order->address->apartment_no)
                                                ، شقة {{ $order->address->apartment_no }}
                                            @endif
                                            @if($order->address->landmark)
                                                <br>
                                                <small class="text-muted">{{ $order->address->landmark }}</small>
                                            @endif
                                        </address>
                                    </li>
                                    @if($order->delivery_notes)
                                        <li class="list-group-item">
                                            <strong>ملاحظات التوصيل:</strong>
                                            <p class="mb-0 mt-1">{{ $order->delivery_notes }}</p>
                                        </li>
                                    @endif
                                    @if($order->expected_delivery_time)
                                        <li class="list-group-item">
                                            <strong>وقت التوصيل المتوقع:</strong>
                                            <p class="mb-0">{{ \Carbon\Carbon::parse($order->expected_delivery_time)->format('d/m/Y - h:i A') }}</p>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- تفاصيل الأطباق المطلوبة -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">الأطباق المطلوبة</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>الطبق</th>
                                        <th>السعر</th>
                                        <th>الكمية</th>
                                        <th>الإجمالي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->dish->image_url }}" alt="{{ $item->dish->name }}" class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->dish->name }}</h6>
                                                        @if($item->options)
                                                            <small class="text-muted">
                                                                @foreach(json_decode($item->options, true) as $key => $value)
                                                                    <span>{{ $key }}: {{ $value }}</span><br>
                                                                @endforeach
                                                            </small>
                                                        @endif
                                                        @if($item->notes)
                                                            <small class="text-muted">ملاحظات: {{ $item->notes }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $item->price }} ريال</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ $item->price * $item->quantity }} ريال</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-start">المجموع الفرعي:</td>
                                            <td class="text-end">{{ $order->subtotal }} ريال</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">رسوم التوصيل:</td>
                                            <td class="text-end">{{ $order->delivery_fee }} ريال</td>
                                        </tr>
                                        @if($order->discount > 0)
                                            <tr>
                                                <td class="text-start">الخصم:</td>
                                                <td class="text-end">- {{ $order->discount }} ريال</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="text-start">الضريبة ({{ $order->tax_percentage }}%):</td>
                                            <td class="text-end">{{ $order->tax_amount }} ريال</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start fw-bold">الإجمالي:</td>
                                            <td class="text-end fw-bold">{{ $order->total }} ريال</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- طلب مشابه -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="{{ route('frontend.chef.show', $order->chef->id) }}" class="btn btn-primary">
                        <i class="fas fa-utensils me-2"></i> طلب من نفس الشيف مرة أخرى
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تأكيد إلغاء الطلب
        const cancelForm = document.querySelector('.cancel-form');
        if (cancelForm) {
            cancelForm.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('هل أنت متأكد من رغبتك في إلغاء هذا الطلب؟')) {
                    this.submit();
                }
            });
        }
    });
</script>
@endsection
