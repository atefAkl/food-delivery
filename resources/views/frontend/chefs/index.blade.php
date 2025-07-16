@extends('frontend.layouts.app')

@section('title', 'الشيفات')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">الشيفات</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>استكشف أفضل الشيفات</h2>
                <div>
                    <a href="{{ route('frontend.search') }}" class="btn btn-outline-primary">
                        <i class="fas fa-utensils me-2"></i> تصفح الأطباق
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- فلتر البحث -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('frontend.chefs') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="search" class="form-label">البحث</label>
                                <input type="text" class="form-control" id="search" name="search" placeholder="ابحث عن شيف..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="speciality" class="form-label">التخصص</label>
                                <select class="form-select" id="speciality" name="speciality">
                                    <option value="">كل التخصصات</option>
                                    @foreach($specialities as $speciality)
                                        <option value="{{ $speciality }}" {{ request('speciality') == $speciality ? 'selected' : '' }}>{{ $speciality }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="sort" class="form-label">الترتيب</label>
                                <select class="form-select" id="sort" name="sort">
                                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>الأعلى تقييماً</option>
                                    <option value="dishes" {{ request('sort') == 'dishes' ? 'selected' : '' }}>الأكثر أطباقاً</option>
                                    <option value="orders" {{ request('sort') == 'orders' ? 'selected' : '' }}>الأكثر طلبات</option>
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

    @if(count($chefs) > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($chefs as $chef)
                <div class="col">
                    <div class="card h-100 chef-card shadow-sm">
                        <div class="position-relative">
                            <img src="{{ $chef->cover_image ?? '/images/default-chef-cover.jpg' }}" class="card-img-top" alt="{{ $chef->user->name }}" style="height: 150px; object-fit: cover;">
                            <div class="chef-avatar">
                                <img src="{{ $chef->user->profile_image ?? '/images/default-user.jpg' }}" class="rounded-circle border border-3 border-white" alt="{{ $chef->user->name }}" width="80" height="80" style="object-fit: cover;">
                            </div>
                        </div>
                        <div class="card-body pt-5 mt-3 text-center">
                            <h5 class="card-title">{{ $chef->user->name }}</h5>
                            <p class="text-muted mb-2">{{ $chef->speciality }}</p>
                            
                            <div class="rating mb-3">
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
                            
                            <div class="chef-stats d-flex justify-content-center mb-3">
                                <div class="me-3">
                                    <i class="fas fa-utensils text-primary"></i>
                                    <span>{{ $chef->dishes_count }} طبق</span>
                                </div>
                                <div>
                                    <i class="fas fa-shopping-bag text-success"></i>
                                    <span>{{ $chef->orders_count }} طلب</span>
                                </div>
                            </div>
                            
                            <p class="card-text small">{{ Str::limit($chef->bio, 100) }}</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-grid gap-2">
                                <a href="{{ route('frontend.chefs.profile', $chef->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-user-chef me-2"></i> عرض الملف الشخصي
                                </a>
                                <a href="{{ route('frontend.chefs.profile', $chef->id) }}" class="btn btn-primary">
                                    <i class="fas fa-utensils me-2"></i> عرض الأطباق
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- الترقيم -->
        <div class="d-flex justify-content-center mt-4">
            {{ $chefs->appends(request()->query())->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> لا يوجد شيفات متطابقين مع معايير البحث.
        </div>
    @endif
    
    <!-- أقسام إضافية -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">استكشف حسب التخصص</h3>
        </div>
        
        <div class="col-md-3 mb-4">
            <a href="{{ route('frontend.chefs', ['speciality' => 'المطبخ الشرقي']) }}" class="text-decoration-none">
                <div class="card text-center h-100 speciality-card">
                    <div class="card-body">
                        <i class="fas fa-utensils fa-3x text-primary mb-3"></i>
                        <h5>المطبخ الشرقي</h5>
                        <p class="text-muted small">أشهى الأطباق العربية والشرقية التقليدية</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-3 mb-4">
            <a href="{{ route('frontend.chefs', ['speciality' => 'المطبخ الإيطالي']) }}" class="text-decoration-none">
                <div class="card text-center h-100 speciality-card">
                    <div class="card-body">
                        <i class="fas fa-pizza-slice fa-3x text-danger mb-3"></i>
                        <h5>المطبخ الإيطالي</h5>
                        <p class="text-muted small">البيتزا والمعكرونة والأطباق الإيطالية الأصيلة</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-3 mb-4">
            <a href="{{ route('frontend.chefs', ['speciality' => 'الحلويات']) }}" class="text-decoration-none">
                <div class="card text-center h-100 speciality-card">
                    <div class="card-body">
                        <i class="fas fa-birthday-cake fa-3x text-warning mb-3"></i>
                        <h5>الحلويات</h5>
                        <p class="text-muted small">كعك، حلويات، ومخبوزات طازجة</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-3 mb-4">
            <a href="{{ route('frontend.chefs', ['speciality' => 'المأكولات البحرية']) }}" class="text-decoration-none">
                <div class="card text-center h-100 speciality-card">
                    <div class="card-body">
                        <i class="fas fa-fish fa-3x text-info mb-3"></i>
                        <h5>المأكولات البحرية</h5>
                        <p class="text-muted small">أطباق السمك والمأكولات البحرية الطازجة</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    
    <!-- انضم كشيف -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h3>هل أنت شيف محترف؟</h3>
                            <p class="mb-lg-0">انضم إلى منصتنا وابدأ في بيع أطباقك للعملاء في منطقتك. سجل الآن وابدأ رحلتك مع أكبر منصة لتوصيل الطعام المنزلي.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <a href="{{ route('frontend.register') }}?type=chef" class="btn btn-light btn-lg">
                                <i class="fas fa-user-plus me-2"></i> انضم كشيف
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
    .chef-card .chef-avatar {
        position: absolute;
        top: 110px;
        left: 50%;
        transform: translateX(-50%);
    }
    
    .speciality-card {
        transition: all 0.3s ease;
    }
    
    .speciality-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endsection
