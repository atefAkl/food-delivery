@extends('frontend.layouts.app')

@section('title', 'طلباتي')

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
    
    <!-- محتوى الطلبات -->
    <div class="col-lg-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">طلباتي</h4>
                <a href="{{ route('frontend.dishes') }}" class="btn btn-primary">
                    <i class="fas fa-utensils me-2"></i> تصفح الأطباق
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                <!-- فلتر الطلبات -->
                <div class="mb-4">
                    <form action="{{ route('frontend.customer.orders') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">كل الحالات</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد التحضير</option>
                                <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>قيد التوصيل</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التوصيل</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="sort" class="form-select">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث أولاً</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم أولاً</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-filter me-2"></i> تصفية
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                @if(count($orders) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>الشيف</th>
                                    <th>المبلغ</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->chef->user->name }}</td>
                                        <td>{{ $order->total }} ريال</td>
                                        <td>
                                            @switch($order->status)
                                                @case('pending')
                                                    <span class="badge bg-warning text-dark">قيد الانتظار</span>
                                                    @break
                                                @case('processing')
                                                    <span class="badge bg-info">قيد التحضير</span>
                                                    @break
                                                @case('shipping')
                                                    <span class="badge bg-primary">قيد التوصيل</span>
                                                    @break
                                                @case('delivered')
                                                    <span class="badge bg-success">تم التوصيل</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">ملغي</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $order->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('frontend.customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> عرض
                                            </a>
                                            @if($order->status === 'pending')
                                                <form action="{{ route('frontend.customer.orders.cancel', $order->id) }}" method="POST" class="d-inline cancel-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-times"></i> إلغاء
                                                    </button>
                                                </form>
                                            @endif
                                            @if($order->status === 'delivered' && !$order->is_rated)
                                                <a href="{{ route('frontend.customer.orders.rate', $order->id) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-star"></i> تقييم
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- الترقيم -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> لا توجد طلبات حتى الآن.
                        <a href="{{ route('frontend.dishes') }}" class="alert-link">تصفح الأطباق وابدأ بالطلب</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تأكيد إلغاء الطلب
        const cancelForms = document.querySelectorAll('.cancel-form');
        cancelForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('هل أنت متأكد من رغبتك في إلغاء هذا الطلب؟')) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection
