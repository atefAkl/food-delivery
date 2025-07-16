@extends('frontend.layouts.app')

@section('title', 'الصفحة الرئيسية')

@section('hero')
<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4">اطلب أشهى الأطباق من أفضل الشيفات</h1>
        <p class="lead mb-5">استمتع بتجربة طعام فريدة من نوعها مع أطباق معدة خصيصًا لك من قبل شيفات محترفين</p>
        <div class="d-flex justify-content-center">
            <a href="{{ route('frontend.dishes') }}" class="btn btn-primary btn-lg me-3">تصفح الأطباق</a>
            <a href="{{ route('frontend.chefs') }}" class="btn btn-outline-light btn-lg">تعرف على الشيفات</a>
        </div>
    </div>
</div>
@endsection

@section('content')
<!-- قسم الأطباق المميزة -->
<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>أطباق مميزة</h2>
        <a href="{{ route('frontend.dishes') }}" class="btn btn-outline-primary">عرض الكل</a>
    </div>

    <div class="row">
        @forelse($featuredDishes as $dish)
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
                            @for($i = 1; $i <= 5; $i++) @if($i <=$dish->average_rating)
                                <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $dish->average_rating)
                                    <i class="fas fa-star-half-alt"></i>
                                    @else
                                    <i class="far fa-star"></i>
                                    @endif
                                    @endfor
                                    <span class="ms-1 text-muted">({{ $dish->ratings_count }})</span>
                        </div>
                        <small class="text-muted">بواسطة: <a href="{{ route('frontend.chefs.profile', $dish->chef->id) }}">{{ $dish->chef->user->name }}</a></small>
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
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                لا توجد أطباق مميزة حاليًا.
            </div>
        </div>
        @endforelse
    </div>
</section>

<!-- قسم الشيفات المميزين -->
<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>شيفات مميزون</h2>
        <a href="{{ route('frontend.chefs') }}" class="btn btn-outline-primary">عرض الكل</a>
    </div>

    <div class="row">
        @forelse($featuredChefs as $chef)
        <div class="col-md-4 mb-4">
            <div class="card chef-card h-100">
                <img src="{{ $chef->user->profile_image ?? '/images/default-chef.jpg' }}" class="card-img-top" alt="{{ $chef->user->name }}">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $chef->user->name }}</h5>
                    <div class="rating mb-2">
                        @for($i = 1; $i <= 5; $i++) @if($i <=$chef->average_rating)
                            <i class="fas fa-star"></i>
                            @elseif($i - 0.5 <= $chef->average_rating)
                                <i class="fas fa-star-half-alt"></i>
                                @else
                                <i class="far fa-star"></i>
                                @endif
                                @endfor
                                <span class="ms-1 text-muted">({{ $chef->ratings_count }})</span>
                    </div>
                    <p class="card-text">{{ Str::limit($chef->user->bio, 100) }}</p>
                    <p class="text-muted mb-0">{{ $chef->dishes_count }} طبق | {{ $chef->orders_count }} طلب</p>
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="{{ route('frontend.chefs.profile', $chef->id) }}" class="btn btn-primary">عرض الأطباق</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                لا يوجد شيفات مميزون حاليًا.
            </div>
        </div>
        @endforelse
    </div>
</section>

<!-- قسم كيف يعمل التطبيق -->
<section class="mb-5">
    <h2 class="text-center mb-5">كيف يعمل التطبيق؟</h2>

    <div class="row">
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-search fa-2x"></i>
                    </div>
                    <h4>اختر طبقك المفضل</h4>
                    <p class="text-muted">تصفح مجموعة متنوعة من الأطباق المعدة بواسطة شيفات محترفين واختر ما يناسب ذوقك.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                    <h4>أضف إلى السلة وأكمل الطلب</h4>
                    <p class="text-muted">أضف الأطباق التي تريدها إلى سلة التسوق، واختر العنوان وطريقة الدفع المناسبة.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-utensils fa-2x"></i>
                    </div>
                    <h4>استمتع بوجبتك</h4>
                    <p class="text-muted">انتظر وصول طلبك واستمتع بتجربة طعام فريدة من نوعها مع أطباق معدة خصيصًا لك.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- قسم الاشتراك في النشرة البريدية -->
<section id="contact" class="bg-light p-5 rounded-3">
    <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <h3>اشترك في نشرتنا البريدية</h3>
            <p class="text-muted mb-0">احصل على آخر العروض والأطباق الجديدة مباشرة إلى بريدك الإلكتروني.</p>
        </div>
        <div class="col-md-6">
            <form action="{{ route('frontend.contact.send') }}" method="POST" class="d-flex">
                @csrf
                <input type="email" name="email" class="form-control me-2" placeholder="أدخل بريدك الإلكتروني" required>
                <button type="submit" class="btn btn-primary">اشتراك</button>
            </form>
        </div>
    </div>
</section>
@endsection