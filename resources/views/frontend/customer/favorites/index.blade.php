@extends('frontend.layouts.app')

@section('title', 'المفضلة')

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
    
    <!-- محتوى المفضلة -->
    <div class="col-lg-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">المفضلة</h4>
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
                
                <!-- فلتر المفضلة -->
                <div class="mb-4">
                    <form action="{{ route('frontend.favorites') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <select name="category" class="form-select">
                                <option value="">كل التصنيفات</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="chef" class="form-select">
                                <option value="">كل الشيفات</option>
                                @foreach($chefs as $chef)
                                    <option value="{{ $chef->id }}" {{ request('chef') == $chef->id ? 'selected' : '' }}>{{ $chef->user->name }}</option>
                                @endforeach
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
                
                @if(count($favorites) > 0)
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @foreach($favorites as $favorite)
                            <div class="col">
                                <div class="card h-100 dish-card">
                                    <div class="position-relative">
                                        <img src="{{ $favorite->dish->image_url }}" class="card-img-top" alt="{{ $favorite->dish->name }}" style="height: 180px; object-fit: cover;">
                                        <form action="{{ route('frontend.favorites.remove', $favorite->dish->id) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm rounded-circle remove-favorite">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $favorite->dish->name }}</h5>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="me-auto">
                                                <span class="badge bg-light text-dark">{{ $favorite->dish->category->name }}</span>
                                            </div>
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $favorite->dish->average_rating)
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i - 0.5 <= $favorite->dish->average_rating)
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                                <small class="text-muted">({{ $favorite->dish->ratings_count }})</small>
                                            </div>
                                        </div>
                                        <p class="card-text text-muted small">{{ Str::limit($favorite->dish->description, 80) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="chef-info d-flex align-items-center">
                                                <img src="{{ $favorite->dish->chef->user->profile_image ?? '/images/default-chef.jpg' }}" class="rounded-circle me-1" width="25" height="25" alt="{{ $favorite->dish->chef->user->name }}">
                                                <small>{{ $favorite->dish->chef->user->name }}</small>
                                            </div>
                                            <span class="fw-bold text-primary">{{ $favorite->dish->price }} ريال</span>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-top-0">
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('frontend.dishes.show', $favorite->dish->id) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-eye me-2"></i> عرض التفاصيل
                                            </a>
                                            <a href="{{ route('frontend.cart.add', $favorite->dish->id) }}" class="btn btn-primary">
                                                <i class="fas fa-shopping-cart me-2"></i> أضف للسلة
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- الترقيم -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $favorites->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> لا توجد أطباق في المفضلة حتى الآن.
                        <a href="{{ route('frontend.dishes') }}" class="alert-link">تصفح الأطباق وأضف المفضلة لديك</a>
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
        // تأكيد إزالة من المفضلة
        const removeFavoriteButtons = document.querySelectorAll('.remove-favorite');
        removeFavoriteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('هل أنت متأكد من رغبتك في إزالة هذا الطبق من المفضلة؟')) {
                    this.closest('form').submit();
                }
            });
        });
    });
</script>
@endsection
