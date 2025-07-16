@extends('frontend.layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<div class="row">
    <!-- القائمة الجانبية -->
    <div class="col-lg-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <img src="{{ Auth::user()->profile_image ?? '/images/default-user.jpg' }}" class="rounded-circle mb-3" alt="{{ Auth::user()->name }}" width="100" height="100" style="object-fit: cover;">
                <h4>{{ Auth::user()->name }}</h4>
                <p class="text-muted">
                    @if(Auth::user()->type === 'chef')
                        <span class="badge bg-success">شيف</span>
                    @elseif(Auth::user()->type === 'customer')
                        <span class="badge bg-primary">عميل</span>
                    @endif
                </p>
                <p class="text-muted">{{ Auth::user()->email }}</p>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('frontend.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.profile') ? 'active' : '' }}">
                    <i class="fas fa-user me-2"></i> المعلومات الشخصية
                </a>
                <a href="{{ route('frontend.profile.edit') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.profile.edit') ? 'active' : '' }}">
                    <i class="fas fa-edit me-2"></i> تعديل الملف الشخصي
                </a>
                <a href="{{ route('frontend.profile.password') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.profile.password') ? 'active' : '' }}">
                    <i class="fas fa-key me-2"></i> تغيير كلمة المرور
                </a>
                
                @if(Auth::user()->type === 'customer')
                    <a href="{{ route('frontend.customer.addresses') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.customer.addresses*') ? 'active' : '' }}">
                        <i class="fas fa-map-marker-alt me-2"></i> العناوين
                    </a>
                    <a href="{{ route('frontend.customer.orders') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.customer.orders*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-bag me-2"></i> الطلبات
                    </a>
                    <a href="{{ route('frontend.favorites') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.favorites') ? 'active' : '' }}">
                        <i class="fas fa-heart me-2"></i> المفضلة
                    </a>
                @elseif(Auth::user()->type === 'chef')
                    <a href="{{ route('frontend.chef.dishes') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.chef.dishes*') ? 'active' : '' }}">
                        <i class="fas fa-utensils me-2"></i> الأطباق
                    </a>
                    <a href="{{ route('frontend.chef.orders') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.chef.orders*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-bag me-2"></i> الطلبات
                    </a>
                    <a href="{{ route('frontend.chef.earnings') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.chef.earnings*') ? 'active' : '' }}">
                        <i class="fas fa-money-bill-wave me-2"></i> الأرباح
                    </a>
                    <a href="{{ route('frontend.chef.ratings') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.chef.ratings') ? 'active' : '' }}">
                        <i class="fas fa-star me-2"></i> التقييمات
                    </a>
                @endif
                
                <form action="{{ route('frontend.logout') }}" method="POST" class="mt-3 px-3 pb-3">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- محتوى الملف الشخصي -->
    <div class="col-lg-9">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0">المعلومات الشخصية</h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>المعلومات الأساسية</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 30%">الاسم:</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>البريد الإلكتروني:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>رقم الهاتف:</th>
                                <td>{{ $user->phone ?? 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <th>تاريخ الانضمام:</th>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h5>معلومات إضافية</h5>
                        <table class="table table-borderless">
                            @if($user->type === 'chef')
                                <tr>
                                    <th style="width: 30%">التخصص:</th>
                                    <td>{{ $user->chef->speciality ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>عدد الأطباق:</th>
                                    <td>{{ $user->chef->dishes->count() }}</td>
                                </tr>
                                <tr>
                                    <th>متوسط التقييم:</th>
                                    <td>
                                        <div class="rating">
                                            @php $avgRating = $user->chef->ratings->avg('rating') ?? 0; @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $avgRating)
                                                    <i class="fas fa-star"></i>
                                                @elseif($i - 0.5 <= $avgRating)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-1">({{ $user->chef->ratings->count() }})</span>
                                        </div>
                                    </td>
                                </tr>
                            @elseif($user->type === 'customer')
                                <tr>
                                    <th style="width: 30%">عدد الطلبات:</th>
                                    <td>{{ $user->customer->orders->count() }}</td>
                                </tr>
                                <tr>
                                    <th>العناوين:</th>
                                    <td>{{ $user->customer->addresses->count() }}</td>
                                </tr>
                                <tr>
                                    <th>المفضلة:</th>
                                    <td>{{ $user->customer->favorites->count() }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                
                @if($user->bio)
                    <div class="mb-4">
                        <h5>نبذة شخصية</h5>
                        <p>{{ $user->bio }}</p>
                    </div>
                @endif
                
                <div class="text-end">
                    <a href="{{ route('frontend.profile.edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> تعديل الملف الشخصي
                    </a>
                </div>
            </div>
        </div>
        
        @if($user->type === 'chef')
            <!-- أحدث الأطباق للشيف -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">أحدث الأطباق</h4>
                    <a href="{{ route('frontend.chef.dishes') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body">
                    @if($user->chef->dishes->count() > 0)
                        <div class="row">
                            @foreach($user->chef->dishes->take(3) as $dish)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <img src="{{ $dish->image_url }}" class="card-img-top" alt="{{ $dish->name }}" style="height: 150px; object-fit: cover;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title mb-0">{{ $dish->name }}</h5>
                                                <span class="badge bg-primary">{{ $dish->price }} ريال</span>
                                            </div>
                                            <div class="rating">
                                                @php $dishRating = $dish->ratings->avg('rating') ?? 0; @endphp
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $dishRating)
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i - 0.5 <= $dishRating)
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <a href="{{ route('frontend.dish.show', $dish->id) }}" class="btn btn-sm btn-outline-primary w-100">عرض التفاصيل</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            لم تقم بإضافة أي أطباق حتى الآن.
                            <a href="{{ route('frontend.chef.dishes.create') }}" class="alert-link">أضف طبقك الأول</a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- أحدث التقييمات للشيف -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">أحدث التقييمات</h4>
                    <a href="{{ route('frontend.chef.ratings') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body">
                    @if($user->chef->ratings->count() > 0)
                        @foreach($user->chef->ratings->take(3) as $rating)
                            <div class="d-flex mb-3">
                                <img src="{{ $rating->customer->user->profile_image ?? '/images/default-user.jpg' }}" class="rounded-circle me-3" alt="{{ $rating->customer->user->name }}" width="50" height="50" style="object-fit: cover;">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="mb-0">{{ $rating->customer->user->name }}</h5>
                                        <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="rating mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating->rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="mb-0">{{ $rating->comment }}</p>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            لا توجد تقييمات حتى الآن.
                        </div>
                    @endif
                </div>
            </div>
        @elseif($user->type === 'customer')
            <!-- أحدث الطلبات للعميل -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">أحدث الطلبات</h4>
                    <a href="{{ route('frontend.customer.orders') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body">
                    @if($user->customer->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>رقم الطلب</th>
                                        <th>التاريخ</th>
                                        <th>المبلغ</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->customer->orders->take(5) as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
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
                                            <td>
                                                <a href="{{ route('frontend.customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">التفاصيل</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            لم تقم بإجراء أي طلبات حتى الآن.
                            <a href="{{ route('frontend.dishes') }}" class="alert-link">تصفح الأطباق</a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- المفضلة للعميل -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">الأطباق المفضلة</h4>
                    <a href="{{ route('frontend.favorites') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body">
                    @if($user->customer->favorites->count() > 0)
                        <div class="row">
                            @foreach($user->customer->favorites->take(3) as $dish)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <img src="{{ $dish->image_url }}" class="card-img-top" alt="{{ $dish->name }}" style="height: 150px; object-fit: cover;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title mb-0">{{ $dish->name }}</h5>
                                                <span class="badge bg-primary">{{ $dish->price }} ريال</span>
                                            </div>
                                            <p class="text-muted small">بواسطة: {{ $dish->chef->user->name }}</p>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <a href="{{ route('frontend.dish.show', $dish->id) }}" class="btn btn-sm btn-outline-primary w-100">عرض التفاصيل</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            لم تقم بإضافة أي أطباق للمفضلة حتى الآن.
                            <a href="{{ route('frontend.dishes') }}" class="alert-link">تصفح الأطباق</a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
