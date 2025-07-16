@extends('frontend.layouts.app')

@section('title', 'لوحة تحكم الشيف')

@section('content')
<div class="row">
    <!-- القائمة الجانبية -->
    <div class="col-lg-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <img src="{{ Auth::user()->profile_image ?? '/images/default-chef.jpg' }}" class="rounded-circle mb-3" alt="{{ Auth::user()->name }}" width="100" height="100" style="object-fit: cover;">
                <h4>{{ Auth::user()->name }}</h4>
                <p class="text-muted">
                    <span class="badge bg-success">شيف</span>
                </p>
                <p class="text-muted">{{ Auth::user()->email }}</p>
            </div>
            <div class="list-group list-group-flush">
                <a href="chef/dashboard" class="list-group-item list-group-item-action {{ request()->routeIs('chef/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> لوحة التحكم
                </a>
                <a href="chef/dishes" class="list-group-item list-group-item-action {{ request()->routeIs('chef/dishes*') ? 'active' : '' }}">
                    <i class="fas fa-utensils me-2"></i> الأطباق
                </a>
                <a href="chef/orders" class="list-group-item list-group-item-action {{ request()->routeIs('chef/orders*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag me-2"></i> الطلبات
                </a>
                <a href="chef/earnings" class="list-group-item list-group-item-action {{ request()->routeIs('chef/earnings*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave me-2"></i> الأرباح
                </a>
                <a href="chef/ratings" class="list-group-item list-group-item-action {{ request()->routeIs('chef/ratings') ? 'active' : '' }}">
                    <i class="fas fa-star me-2"></i> التقييمات
                </a>
                <a href="chef/profile" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.profile') ? 'active' : '' }}">
                    <i class="fas fa-user me-2"></i> الملف الشخصي
                </a>

                <form action="chef/logout" method="POST" class="mt-3 px-3 pb-3">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- محتوى لوحة التحكم -->
    <div class="col-lg-9">
        <h2 class="mb-4">مرحباً، {{ Auth::user()->name }}!</h2>

        <!-- البطاقات الإحصائية -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">الأطباق</h6>
                                <h2 class="mb-0 mt-2">{{ $totalDishes }}</h2>
                            </div>
                            <i class="fas fa-utensils fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a href="chef/dishes" class="text-white text-decoration-none small">عرض التفاصيل</a>
                        <i class="fas fa-angle-left text-white"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">الطلبات</h6>
                                <h2 class="mb-0 mt-2">{{ $totalOrders }}</h2>
                            </div>
                            <i class="fas fa-shopping-bag fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a href="chef/orders" class="text-white text-decoration-none small">عرض التفاصيل</a>
                        <i class="fas fa-angle-left text-white"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">التقييمات</h6>
                                <h2 class="mb-0 mt-2">{{ $recentReviews }}</h2>
                            </div>
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a href="chef/ratings" class="text-white text-decoration-none small">عرض التفاصيل</a>
                        <i class="fas fa-angle-left text-white"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-dark h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">الأرباح</h6>
                                <h2 class="mb-0 mt-2">{{ $totalEarnings }} ريال</h2>
                            </div>
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a href="chef/earnings" class="text-dark text-decoration-none small">عرض التفاصيل</a>
                        <i class="fas fa-angle-left text-dark"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- الطلبات الأخيرة -->
            <div class="col-md-7 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">الطلبات الأخيرة</h5>
                        <a href="chef/orders" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                    </div>
                    <div class="card-body">
                        @if(count($recentOrders) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>رقم الطلب</th>
                                        <th>العميل</th>
                                        <th>المبلغ</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->customer->user->name }}</td>
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
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-info">
                            لا توجد طلبات حتى الآن.
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- التقييمات الأخيرة -->
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">أحدث التقييمات</h5>
                        <a href="chef/ratings" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                    </div>
                    <div class="card-body">

                        @forelse($recentReviews as $review)
                        <div class="d-flex mb-3">
                            <img src="{{ $review->customer->user->profile_image ?? '/images/default-user.jpg' }}" class="rounded-circle me-3" alt="{{ $review->customer->user->name }}" width="50" height="50" style="object-fit: cover;">
                            <div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">{{ $review->customer->user->name }}</h6>
                                    <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="rating mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <=$rating->rating)
                                        <i class="fas fa-star"></i>
                                        @else
                                        <i class="far fa-star"></i>
                                        @endif
                                        @endfor
                                </div>
                                <p class="mb-0 small">{{ Str::limit($rating->comment, 100) }}</p>
                                <small class="text-muted">طبق: {{ $rating->dish->name }}</small>
                            </div>
                        </div>
                        @if(!$loop->last)
                        <hr>
                        @endif
                        @empty
                        <div class="alert alert-info">
                            لا توجد تقييمات حتى الآن.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- الأطباق الأكثر مبيعًا -->
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">الأطباق الأكثر مبيعًا</h5>
                        <a href="chef/dishes" class="btn btn-sm btn-outline-primary">عرض كل الأطباق</a>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            @forelse($topDishes as $dish)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <img src="{{ $dish->image_url }}" class="card-img-top" alt="{{ $dish->name }}" style="height: 150px; object-fit: cover;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title mb-0">{{ $dish->name }}</h5>
                                            <span class="badge bg-primary">{{ $dish->price }} ريال</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="rating">
                                                @php $avgRating = $dish->ratings->avg('rating') ?? 0; @endphp
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <=$avgRating)
                                                    <i class="fas fa-star"></i>
                                                    @elseif($i - 0.5 <= $avgRating)
                                                        <i class="fas fa-star-half-alt"></i>
                                                        @else
                                                        <i class="far fa-star"></i>
                                                        @endif
                                                        @endfor
                                            </div>
                                            <span class="badge bg-success">{{ $dish->orders_count }} طلب</span>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <a href="chef/dishes/edit/{{ $dish->id }}" class="btn btn-sm btn-outline-primary w-100">تعديل</a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="alert alert-info">
                                لم تقم بإضافة أي أطباق حتى الآن.
                                <a href="chef/dishes/create" class="alert-link">أضف طبقك الأول</a>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- إضافة طبق جديد -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-3">
                <a href="chef/dishes/create" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle me-2"></i> إضافة طبق جديد
                </a>
            </div>
        </div>
    </div>
    @endsection