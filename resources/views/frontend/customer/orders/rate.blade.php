@extends('frontend.layouts.app')

@section('title', 'تقييم الطلب')

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
    
    <!-- محتوى تقييم الطلب -->
    <div class="col-lg-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">تقييم الطلب #{{ $order->id }}</h4>
                <a href="{{ route('frontend.customer.orders.show', $order->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right me-2"></i> العودة للطلب
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> شكراً لطلبك! تقييمك يساعدنا على تحسين خدماتنا ويساعد الشيف على تطوير أطباقه.
                        </div>
                    </div>
                </div>
                
                <!-- معلومات الشيف -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $order->chef->user->profile_image ?? '/images/default-chef.jpg' }}" class="rounded-circle me-3" alt="{{ $order->chef->user->name }}" width="80" height="80" style="object-fit: cover;">
                                    <div>
                                        <h5 class="mb-1">{{ $order->chef->user->name }}</h5>
                                        <p class="text-muted mb-0">{{ $order->chef->speciality }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- نموذج التقييم -->
                <form action="{{ route('frontend.customer.orders.submit-rating', $order->id) }}" method="POST">
                    @csrf
                    
                    <!-- تقييم الشيف -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">تقييم الشيف</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">التقييم العام للشيف <span class="text-danger">*</span></label>
                                <div class="rating-stars mb-3">
                                    <div class="d-flex flex-row-reverse justify-content-end">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" id="chef-rating-{{ $i }}" name="chef_rating" value="{{ $i }}" class="d-none" {{ old('chef_rating') == $i ? 'checked' : '' }} required>
                                            <label for="chef-rating-{{ $i }}" class="fs-2 me-2"><i class="far fa-star"></i></label>
                                        @endfor
                                    </div>
                                </div>
                                @error('chef_rating')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="chef_comment" class="form-label">تعليق عن الشيف</label>
                                <textarea class="form-control @error('chef_comment') is-invalid @enderror" id="chef_comment" name="chef_comment" rows="3" placeholder="اكتب تعليقك عن الشيف هنا...">{{ old('chef_comment') }}</textarea>
                                @error('chef_comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- تقييم الأطباق -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">تقييم الأطباق</h5>
                        </div>
                        <div class="card-body">
                            @foreach($order->items as $index => $item)
                                <div class="dish-rating mb-4 {{ !$loop->last ? 'border-bottom pb-4' : '' }}">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ $item->dish->image_url }}" alt="{{ $item->dish->name }}" class="rounded me-3" width="70" height="70" style="object-fit: cover;">
                                        <div>
                                            <h6 class="mb-1">{{ $item->dish->name }}</h6>
                                            <p class="text-muted mb-0">{{ $item->quantity }} × {{ $item->price }} ريال</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">تقييم الطبق <span class="text-danger">*</span></label>
                                        <div class="rating-stars mb-3">
                                            <div class="d-flex flex-row-reverse justify-content-end">
                                                @for($i = 5; $i >= 1; $i--)
                                                    <input type="radio" id="dish-rating-{{ $index }}-{{ $i }}" name="dish_ratings[{{ $item->dish->id }}]" value="{{ $i }}" class="d-none" {{ old('dish_ratings.'.$item->dish->id) == $i ? 'checked' : '' }} required>
                                                    <label for="dish-rating-{{ $index }}-{{ $i }}" class="fs-2 me-2"><i class="far fa-star"></i></label>
                                                @endfor
                                            </div>
                                        </div>
                                        @error('dish_ratings.'.$item->dish->id)
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="dish_comment_{{ $item->dish->id }}" class="form-label">تعليق عن الطبق</label>
                                        <textarea class="form-control @error('dish_comments.'.$item->dish->id) is-invalid @enderror" id="dish_comment_{{ $item->dish->id }}" name="dish_comments[{{ $item->dish->id }}]" rows="2" placeholder="اكتب تعليقك عن الطبق هنا...">{{ old('dish_comments.'.$item->dish->id) }}</textarea>
                                        @error('dish_comments.'.$item->dish->id)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- تقييم التوصيل -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">تقييم التوصيل</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">تقييم خدمة التوصيل <span class="text-danger">*</span></label>
                                <div class="rating-stars mb-3">
                                    <div class="d-flex flex-row-reverse justify-content-end">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" id="delivery-rating-{{ $i }}" name="delivery_rating" value="{{ $i }}" class="d-none" {{ old('delivery_rating') == $i ? 'checked' : '' }} required>
                                            <label for="delivery-rating-{{ $i }}" class="fs-2 me-2"><i class="far fa-star"></i></label>
                                        @endfor
                                    </div>
                                </div>
                                @error('delivery_rating')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="delivery_comment" class="form-label">تعليق عن التوصيل</label>
                                <textarea class="form-control @error('delivery_comment') is-invalid @enderror" id="delivery_comment" name="delivery_comment" rows="2" placeholder="اكتب تعليقك عن خدمة التوصيل هنا...">{{ old('delivery_comment') }}</textarea>
                                @error('delivery_comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('frontend.customer.orders.show', $order->id) }}" class="btn btn-outline-secondary me-md-2">إلغاء</a>
                        <button type="submit" class="btn btn-primary">إرسال التقييم</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .rating-stars label {
        color: #ddd;
        cursor: pointer;
    }
    
    .rating-stars label:hover,
    .rating-stars label:hover ~ label,
    .rating-stars input:checked ~ label {
        color: #ffc107;
    }
    
    .rating-stars label i.far.fa-star {
        font-size: 1.5rem;
    }
    
    .rating-stars input:checked ~ label i.far.fa-star:before {
        content: "\f005";
        font-weight: 900;
    }
    
    .rating-stars label:hover i.far.fa-star:before,
    .rating-stars label:hover ~ label i.far.fa-star:before {
        content: "\f005";
        font-weight: 900;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تحويل النجوم إلى مملوءة عند التحديد
        const ratingInputs = document.querySelectorAll('.rating-stars input');
        ratingInputs.forEach(input => {
            input.addEventListener('change', function() {
                const name = this.getAttribute('name');
                const value = this.value;
                
                document.querySelectorAll(`input[name="${name}"] ~ label i`).forEach(icon => {
                    icon.className = 'far fa-star';
                });
                
                document.querySelectorAll(`input[name="${name}"]:checked ~ label i`).forEach(icon => {
                    icon.className = 'fas fa-star';
                });
            });
        });
        
        // تحديد النجوم المحددة مسبقًا
        document.querySelectorAll('.rating-stars input:checked').forEach(input => {
            const event = new Event('change');
            input.dispatchEvent(event);
        });
    });
</script>
@endsection
