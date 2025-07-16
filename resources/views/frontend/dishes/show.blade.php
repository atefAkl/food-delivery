@extends('frontend.layouts.app')

@section('title', $dish->name)

@section('content')
<div class="row">
    <!-- صورة الطبق -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <img src="{{ $dish->image_url }}" class="card-img-top img-fluid" alt="{{ $dish->name }}" style="height: 400px; object-fit: cover;">
            <div class="card-body p-2">
                <div class="row g-0">
                    @foreach($dish->images as $image)
                        <div class="col-3 p-1">
                            <img src="{{ $image->url }}" class="img-thumbnail dish-thumbnail" alt="{{ $dish->name }}" style="height: 80px; object-fit: cover; cursor: pointer;" onclick="document.querySelector('.card-img-top').src = this.src">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- تفاصيل الطبق -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h2 class="card-title">{{ $dish->name }}</h2>
                    <span class="badge bg-primary fs-5">{{ $dish->price }} ريال</span>
                </div>
                
                <div class="mb-3">
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
                        <span class="ms-1">({{ $dish->ratings_count }} تقييم)</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <span class="badge bg-secondary me-1">{{ $dish->category->name }}</span>
                    @if($dish->is_featured)
                        <span class="badge bg-warning text-dark">مميز</span>
                    @endif
                </div>
                
                <p class="card-text">{{ $dish->description }}</p>
                
                <div class="mb-3">
                    <h5>المكونات:</h5>
                    <ul class="list-group list-group-flush">
                        @foreach(explode(',', $dish->ingredients) as $ingredient)
                            <li class="list-group-item bg-transparent ps-0"><i class="fas fa-check-circle text-success me-2"></i>{{ trim($ingredient) }}</li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h5>معلومات إضافية:</h5>
                    <div class="row">
                        <div class="col-6">
                            <p><i class="fas fa-clock me-2 text-primary"></i>وقت التحضير: {{ $dish->preparation_time }} دقيقة</p>
                        </div>
                        <div class="col-6">
                            <p><i class="fas fa-fire me-2 text-danger"></i>السعرات الحرارية: {{ $dish->calories }} سعرة</p>
                        </div>
                        <div class="col-6">
                            <p><i class="fas fa-utensils me-2 text-success"></i>عدد الحصص: {{ $dish->servings }}</p>
                        </div>
                        <div class="col-6">
                            <p><i class="fas fa-shopping-basket me-2 text-warning"></i>الطلبات: {{ $dish->orders_count }}</p>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <!-- معلومات الشيف -->
                <div class="d-flex align-items-center mb-4">
                    <img src="{{ $dish->chef->user->profile_image ?? '/images/default-chef.jpg' }}" class="rounded-circle me-3" alt="{{ $dish->chef->user->name }}" width="60" height="60" style="object-fit: cover;">
                    <div>
                        <h5 class="mb-1">بواسطة: {{ $dish->chef->user->name }}</h5>
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $dish->chef->average_rating)
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $dish->chef->average_rating)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                            <span class="ms-1">({{ $dish->chef->ratings_count }})</span>
                        </div>
                        <a href="{{ route('frontend.chef.profile', $dish->chef->id) }}" class="btn btn-sm btn-outline-primary mt-2">عرض الملف الشخصي</a>
                    </div>
                </div>
                
                @auth
                    @if(Auth::user()->type === 'customer')
                        <hr>
                        <!-- نموذج إضافة للسلة -->
                        <form action="{{ route('frontend.cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="dish_id" value="{{ $dish->id }}">
                            
                            <div class="mb-3">
                                <label for="quantity" class="form-label">الكمية:</label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="decrementQuantity()"><i class="fas fa-minus"></i></button>
                                    <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="10">
                                    <button type="button" class="btn btn-outline-secondary" onclick="incrementQuantity()"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">ملاحظات خاصة (اختياري):</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="أي تعليمات خاصة للشيف..."></textarea>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-cart-plus me-2"></i> إضافة إلى السلة
                                </button>
                                
                                @if(Auth::user()->customer->favorites->contains($dish->id))
                                    <form action="{{ route('frontend.favorites.remove', $dish->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-heart me-2"></i> إزالة من المفضلة
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('frontend.favorites.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="dish_id" value="{{ $dish->id }}">
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="far fa-heart me-2"></i> إضافة للمفضلة
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </form>
                    @endif
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> يرجى <a href="{{ route('frontend.login') }}">تسجيل الدخول</a> لإضافة هذا الطبق إلى سلة التسوق.
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- التقييمات والمراجعات -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-white">
        <h3 class="mb-0">التقييمات والمراجعات ({{ $dish->ratings_count }})</h3>
    </div>
    <div class="card-body">
        @if($dish->ratings->count() > 0)
            @foreach($dish->ratings as $rating)
                <div class="d-flex mb-4">
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
            
            <!-- الترقيم -->
            <div class="d-flex justify-content-center mt-4">
                {{ $dish->ratings->links() }}
            </div>
        @else
            <div class="alert alert-info">
                لا توجد تقييمات لهذا الطبق حتى الآن.
            </div>
        @endif
        
        @auth
            @if(Auth::user()->type === 'customer' && Auth::user()->customer->orders->whereIn('status', ['delivered'])->whereHas('orderItems', function($query) use ($dish) {
                $query->where('dish_id', $dish->id);
            })->count() > 0 && !Auth::user()->customer->ratings->where('dish_id', $dish->id)->exists())
                <hr>
                <h4>أضف تقييمك</h4>
                <form action="{{ route('frontend.ratings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="dish_id" value="{{ $dish->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">التقييم:</label>
                        <div class="rating-input">
                            <div class="d-flex">
                                @for($i = 5; $i >= 1; $i--)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rating{{ $i }}">{{ $i }}</label>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comment" class="form-label">التعليق:</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" required>{{ old('comment') }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">إرسال التقييم</button>
                </form>
            @endif
        @endauth
    </div>
</div>

<!-- أطباق مشابهة -->
<div class="mt-5">
    <h3 class="mb-4">أطباق مشابهة قد تعجبك</h3>
    
    <div class="row">
        @foreach($similarDishes as $similarDish)
            <div class="col-md-3 mb-4">
                <div class="card dish-card h-100">
                    <img src="{{ $similarDish->image_url }}" class="card-img-top" alt="{{ $similarDish->name }}" style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $similarDish->name }}</h5>
                            <span class="badge bg-primary">{{ $similarDish->price }} ريال</span>
                        </div>
                        <div class="rating mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $similarDish->average_rating)
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $similarDish->average_rating)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{ route('frontend.dish.show', $similarDish->id) }}" class="btn btn-sm btn-outline-primary w-100">عرض التفاصيل</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
    function incrementQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        if (currentValue < 10) {
            quantityInput.value = currentValue + 1;
        }
    }
    
    function decrementQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    }
</script>
@endsection
