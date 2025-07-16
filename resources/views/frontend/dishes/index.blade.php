@extends('frontend.layouts.app')

@section('title', 'استعراض الأطباق')

@section('content')
<div class="row">
    <!-- قسم الفلترة والبحث -->
    <div class="col-lg-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">خيارات البحث والتصفية</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('frontend.dishes') }}" method="GET">
                    <!-- البحث -->
                    <div class="mb-3">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="ابحث عن طبق...">
                    </div>
                    
                    <!-- التصنيفات -->
                    <div class="mb-3">
                        <label class="form-label">التصنيفات</label>
                        <div class="overflow-auto" style="max-height: 150px;">
                            @foreach($categories as $category)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category{{ $category->id }}" 
                                        {{ (request('categories') && in_array($category->id, request('categories'))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- نطاق السعر -->
                    <div class="mb-3">
                        <label class="form-label">نطاق السعر (ريال)</label>
                        <div class="d-flex">
                            <input type="number" class="form-control me-2" name="min_price" value="{{ request('min_price') }}" placeholder="من" min="0">
                            <input type="number" class="form-control" name="max_price" value="{{ request('max_price') }}" placeholder="إلى">
                        </div>
                    </div>
                    
                    <!-- التقييم -->
                    <div class="mb-3">
                        <label class="form-label">التقييم</label>
                        <select class="form-select" name="rating">
                            <option value="">الكل</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 نجوم</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 نجوم وأعلى</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 نجوم وأعلى</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 نجوم وأعلى</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 نجمة وأعلى</option>
                        </select>
                    </div>
                    
                    <!-- الترتيب -->
                    <div class="mb-3">
                        <label class="form-label">ترتيب حسب</label>
                        <select class="form-select" name="sort">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>التقييم</option>
                            <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>الشعبية</option>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">تطبيق الفلتر</button>
                        <a href="{{ route('frontend.dishes') }}" class="btn btn-outline-secondary">إعادة تعيين</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- قسم عرض الأطباق -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>استعراض الأطباق</h2>
            <div class="d-flex align-items-center">
                <span class="me-2">عرض:</span>
                <div class="btn-group" role="group">
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" class="btn btn-outline-primary {{ request('view', 'grid') == 'grid' ? 'active' : '' }}">
                        <i class="fas fa-th"></i>
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" class="btn btn-outline-primary {{ request('view') == 'list' ? 'active' : '' }}">
                        <i class="fas fa-list"></i>
                    </a>
                </div>
            </div>
        </div>
        
        @if($dishes->count() > 0)
            @if(request('view') == 'list')
                <!-- عرض القائمة -->
                <div class="list-group">
                    @foreach($dishes as $dish)
                        <div class="list-group-item list-group-item-action p-0 mb-3 border rounded overflow-hidden">
                            <div class="row g-0">
                                <div class="col-md-3">
                                    <img src="{{ $dish->image_url }}" class="img-fluid h-100" style="object-fit: cover;" alt="{{ $dish->name }}">
                                </div>
                                <div class="col-md-9">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h5 class="card-title">{{ $dish->name }}</h5>
                                            <span class="badge bg-primary">{{ $dish->price }} ريال</span>
                                        </div>
                                        <p class="card-text text-muted">{{ Str::limit($dish->description, 150) }}</p>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $dish->average_rating)
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i - 0.5 <= $dish->average_rating)
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-1 text-muted">({{ $dish->ratings_count }})</span>
                                            </div>
                                            <small class="text-muted">بواسطة: <a href="{{ route('frontend.chef.profile', $dish->chef->id) }}">{{ $dish->chef->user->name }}</a></small>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge bg-secondary me-1">{{ $dish->category->name }}</span>
                                                @if($dish->is_featured)
                                                    <span class="badge bg-warning text-dark">مميز</span>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('frontend.dish.show', $dish->id) }}" class="btn btn-sm btn-outline-primary me-2">التفاصيل</a>
                                                @auth
                                                    @if(Auth::user()->type === 'customer')
                                                        <form action="{{ route('frontend.cart.add') }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="dish_id" value="{{ $dish->id }}">
                                                            <input type="hidden" name="quantity" value="1">
                                                            <button type="submit" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-cart-plus me-1"></i> إضافة للسلة
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- عرض الشبكة -->
                <div class="row">
                    @foreach($dishes as $dish)
                        <div class="col-md-4 mb-4">
                            <div class="card dish-card h-100">
                                <img src="{{ $dish->image_url }}" class="card-img-top" alt="{{ $dish->name }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $dish->name }}</h5>
                                        <span class="badge bg-primary">{{ $dish->price }} ريال</span>
                                    </div>
                                    <p class="card-text text-muted">{{ Str::limit($dish->description, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $dish->average_rating)
                                                    <i class="fas fa-star"></i>
                                                @elseif($i - 0.5 <= $dish->average_rating)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-1 text-muted">({{ $dish->ratings_count }})</span>
                                        </div>
                                        <span class="badge bg-secondary">{{ $dish->category->name }}</span>
                                    </div>
                                </div>
                                <div class="card-footer bg-white d-flex justify-content-between">
                                    <a href="{{ route('frontend.dish.show', $dish->id) }}" class="btn btn-sm btn-outline-primary">التفاصيل</a>
                                    @auth
                                        @if(Auth::user()->type === 'customer')
                                            <form action="{{ route('frontend.cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="dish_id" value="{{ $dish->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-cart-plus me-1"></i> إضافة للسلة
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
            <!-- الترقيم -->
            <div class="d-flex justify-content-center mt-4">
                {{ $dishes->appends(request()->query())->links() }}
            </div>
        @else
            <div class="alert alert-info">
                لم يتم العثور على أطباق تطابق معايير البحث. يرجى تعديل الفلتر والمحاولة مرة أخرى.
            </div>
        @endif
    </div>
</div>
@endsection
