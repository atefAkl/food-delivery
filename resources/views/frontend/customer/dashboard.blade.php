@extends('frontend.layouts.app')

@section('title', 'لوحة تحكم العميل')

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
    
    <!-- محتوى لوحة التحكم -->
    <div class="col-lg-9">
        <h2 class="mb-4">مرحباً، {{ Auth::user()->name }}!</h2>
        
        <!-- البطاقات الإحصائية -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">طلباتي</h6>
                                <h2 class="mb-0 mt-2">{{ $ordersCount }}</h2>
                            </div>
                            <i class="fas fa-shopping-bag fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a href="{{ route('frontend.customer.orders') }}" class="text-white text-decoration-none small">عرض التفاصيل</a>
                        <i class="fas fa-angle-left text-white"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">المفضلة</h6>
                                <h2 class="mb-0 mt-2">{{ $favoritesCount }}</h2>
                            </div>
                            <i class="fas fa-heart fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a href="{{ route('frontend.favorites') }}" class="text-white text-decoration-none small">عرض التفاصيل</a>
                        <i class="fas fa-angle-left text-white"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">عناويني</h6>
                                <h2 class="mb-0 mt-2">{{ $addressesCount }}</h2>
                            </div>
                            <i class="fas fa-map-marker-alt fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a href="{{ route('frontend.customer.addresses') }}" class="text-white text-decoration-none small">عرض التفاصيل</a>
                        <i class="fas fa-angle-left text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- الطلبات الأخيرة -->
            <div class="col-md-7 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">طلباتي الأخيرة</h5>
                        <a href="{{ route('frontend.customer.orders') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                    </div>
                    <div class="card-body">
                        @if(count($recentOrders) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>رقم الطلب</th>
                                            <th>الشيف</th>
                                            <th>المبلغ</th>
                                            <th>الحالة</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $order)
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
            
            <!-- الأطباق المفضلة -->
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">الأطباق المفضلة</h5>
                        <a href="{{ route('frontend.favorites') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                    </div>
                    <div class="card-body">
                        @if(count($favoriteDishes) > 0)
                            @foreach($favoriteDishes as $dish)
                                <div class="d-flex mb-3">
                                    <img src="{{ $dish->image_url }}" class="rounded me-3" alt="{{ $dish->name }}" width="70" height="70" style="object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h6 class="mb-0">{{ $dish->name }}</h6>
                                            <span class="badge bg-primary">{{ $dish->price }} ريال</span>
                                        </div>
                                        <p class="text-muted small mb-1">{{ Str::limit($dish->description, 50) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">الشيف: {{ $dish->chef->user->name }}</small>
                                            <div class="rating">
                                                @php $avgRating = $dish->ratings->avg('rating') ?? 0; @endphp
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $avgRating)
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i - 0.5 <= $avgRating)
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr>
                                @endif
                            @endforeach
                        @else
                            <div class="alert alert-info">
                                لا توجد أطباق مفضلة حتى الآن.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- الشيفات الموصى بهم -->
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">الشيفات الموصى بهم</h5>
                        <a href="{{ route('frontend.chefs') }}" class="btn btn-sm btn-outline-primary">عرض كل الشيفات</a>
                    </div>
                    <div class="card-body">
                        @if(count($recommendedChefs) > 0)
                            <div class="row">
                                @foreach($recommendedChefs as $chef)
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100">
                                            <img src="{{ $chef->user->profile_image ?? '/images/default-chef.jpg' }}" class="card-img-top" alt="{{ $chef->user->name }}" style="height: 150px; object-fit: cover;">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $chef->user->name }}</h5>
                                                <p class="card-text text-muted small">{{ Str::limit($chef->speciality, 50) }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="rating">
                                                        @php $avgRating = $chef->ratings->avg('rating') ?? 0; @endphp
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $avgRating)
                                                                <i class="fas fa-star"></i>
                                                            @elseif($i - 0.5 <= $avgRating)
                                                                <i class="fas fa-star-half-alt"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span class="badge bg-success">{{ $chef->dishes_count }} طبق</span>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-white">
                                                <a href="{{ route('frontend.chefs.show', $chef->id) }}" class="btn btn-sm btn-outline-primary w-100">عرض الأطباق</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                لا يوجد شيفات موصى بهم حاليًا.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- البحث عن أطباق -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">ابحث عن أطباق جديدة</h5>
                <form action="{{ route('frontend.dishes') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="ابحث عن أطباق، شيفات، أو مكونات..." name="search">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
