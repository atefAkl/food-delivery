@extends('frontend.layouts.app')

@section('title', 'أطباق الشيف ' . $chef->user->name)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('frontend.chefs') }}">الشيفات</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('frontend.chef.show', $chef->id) }}">{{ $chef->user->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">الأطباق</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- معلومات الشيف المختصرة -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="{{ $chef->user->profile_image ?? '/images/default-user.jpg' }}" class="rounded-circle me-3" alt="{{ $chef->user->name }}" width="80" height="80" style="object-fit: cover;">
                        <div>
                            <h3 class="mb-1">أطباق الشيف {{ $chef->user->name }}</h3>
                            <div class="d-flex align-items-center">
                                <div class="rating me-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $chef->average_rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif($i - 0.5 <= $chef->average_rating)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-1">({{ $chef->ratings_count }})</span>
                                </div>
                                <span class="badge bg-primary me-2">{{ $chef->speciality }}</span>
                                <span><i class="fas fa-utensils text-muted me-1"></i> {{ $dishes->total() }} طبق</span>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('frontend.chef.show', $chef->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-user me-2"></i> عرض الملف الشخصي
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- فلتر البحث -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('frontend.chef.dishes', $chef->id) }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="search" class="form-label">البحث</label>
                                <input type="text" class="form-control" id="search" name="search" placeholder="ابحث عن طبق..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="category" class="form-label">التصنيف</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">كل التصنيفات</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="sort" class="form-label">الترتيب</label>
                                <select class="form-select" id="sort" name="sort">
                                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>الأكثر شعبية</option>
                                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>الأعلى تقييماً</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="d-grid w-100">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i> بحث
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- قائمة الأطباق -->
    @if(count($dishes) > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($dishes as $dish)
                <div class="col">
                    <div class="card h-100 dish-card shadow-sm">
                        <div class="position-relative">
                            <img src="{{ $dish->image_url }}" class="card-img-top" alt="{{ $dish->name }}" style="height: 200px; object-fit: cover;">
                            @if($dish->is_featured)
                                <span class="position-absolute top-0 start-0 badge bg-warning text-dark m-2">مميز</span>
                            @endif
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-primary">{{ $dish->price }} ريال</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $dish->name }}</h5>
                            <div class="rating mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $dish->average_rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @elseif($i - 0.5 <= $dish->average_rating)
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                <span class="ms-1">({{ $dish->ratings_count }})</span>
                            </div>
                            <p class="card-text">{{ Str::limit($dish->description, 100) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-secondary">{{ $dish->category->name }}</span>
                                <span><i class="fas fa-clock text-muted me-1"></i> {{ $dish->preparation_time }} دقيقة</span>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-grid gap-2">
                                <a href="{{ route('frontend.dishes.show', $dish->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i> عرض التفاصيل
                                </a>
                                @auth
                                    @if(Auth::user()->type === 'customer')
                                        <form action="{{ route('frontend.cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="dish_id" value="{{ $dish->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-cart-plus me-2"></i> إضافة للسلة
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- الترقيم -->
        <div class="d-flex justify-content-center mt-4">
            {{ $dishes->appends(request()->query())->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> لا توجد أطباق متطابقة مع معايير البحث.
        </div>
    @endif

    <!-- أقسام إضافية -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h4>هل تبحث عن المزيد من الخيارات؟</h4>
                            <p class="mb-lg-0">استكشف مجموعة متنوعة من الأطباق من مختلف الشيفات في منصتنا.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <a href="{{ route('frontend.dishes') }}" class="btn btn-primary">
                                <i class="fas fa-utensils me-2"></i> تصفح جميع الأطباق
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .dish-card {
        transition: all 0.3s ease;
    }
    
    .dish-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endsection
