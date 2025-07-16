@extends('frontend.layouts.app')

@section('title', $chef->user->name)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('frontend.chefs') }}">الشيفات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $chef->user->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- معلومات الشيف -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="position-relative">
                    <img src="{{ $chef->cover_image ?? '/images/default-chef-cover.jpg' }}" class="card-img-top" alt="{{ $chef->user->name }}" style="height: 250px; object-fit: cover;">
                    <div class="chef-avatar">
                        <img src="{{ $chef->user->profile_image ?? '/images/default-user.jpg' }}" class="rounded-circle border border-4 border-white" alt="{{ $chef->user->name }}" width="120" height="120" style="object-fit: cover;">
                    </div>
                </div>
                <div class="card-body pt-5 mt-4">
                    <div class="row">
                        <div class="col-md-8">
                            <h2 class="card-title mb-1">{{ $chef->user->name }}</h2>
                            <p class="text-muted mb-3">{{ $chef->speciality }}</p>
                            
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
                                <span class="ms-1">({{ $chef->ratings_count }} تقييم)</span>
                            </div>
                            
                            <div class="chef-bio mb-4">
                                <h5>نبذة عن الشيف</h5>
                                <p>{{ $chef->bio }}</p>
                            </div>
                            
                            @if($chef->experience)
                                <div class="chef-experience mb-4">
                                    <h5>الخبرات</h5>
                                    <p>{{ $chef->experience }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">إحصائيات</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-utensils text-primary me-2"></i> الأطباق</span>
                                            <span class="badge bg-primary rounded-pill">{{ $chef->dishes_count }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-shopping-bag text-success me-2"></i> الطلبات المكتملة</span>
                                            <span class="badge bg-success rounded-pill">{{ $chef->completed_orders_count }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-users text-info me-2"></i> العملاء</span>
                                            <span class="badge bg-info rounded-pill">{{ $chef->customers_count }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-calendar-alt text-secondary me-2"></i> تاريخ الانضمام</span>
                                            <span>{{ $chef->user->created_at->format('Y/m/d') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">التواصل</h5>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('frontend.chef.dishes', $chef->id) }}" class="btn btn-primary">
                                            <i class="fas fa-utensils me-2"></i> عرض الأطباق
                                        </a>
                                        @auth
                                            @if(Auth::user()->type === 'customer')
                                                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#messageModal">
                                                    <i class="fas fa-envelope me-2"></i> مراسلة الشيف
                                                </button>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- أطباق الشيف -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>أطباق الشيف</h3>
                <a href="{{ route('frontend.chef.dishes', $chef->id) }}" class="btn btn-outline-primary">
                    عرض كل الأطباق <i class="fas fa-arrow-left me-2"></i>
                </a>
            </div>
            
            @if(count($featuredDishes) > 0)
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    @foreach($featuredDishes as $dish)
                        <div class="col">
                            <div class="card h-100 dish-card shadow-sm">
                                <div class="position-relative">
                                    <img src="{{ $dish->image_url }}" class="card-img-top" alt="{{ $dish->name }}" style="height: 180px; object-fit: cover;">
                                    @if($dish->is_featured)
                                        <span class="position-absolute top-0 start-0 badge bg-warning text-dark m-2">مميز</span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $dish->name }}</h5>
                                        <span class="badge bg-primary">{{ $dish->price }} ريال</span>
                                    </div>
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
                                    <p class="card-text small">{{ Str::limit($dish->description, 80) }}</p>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="d-grid">
                                        <a href="{{ route('frontend.dishes.show', $dish->id) }}" class="btn btn-outline-primary">عرض التفاصيل</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> لا توجد أطباق متاحة حالياً لهذا الشيف.
                </div>
            @endif
        </div>
    </div>

    <!-- التقييمات -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">تقييمات العملاء</h3>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h1 class="display-4 fw-bold">{{ number_format($chef->average_rating, 1) }}</h1>
                                <div class="rating mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $chef->average_rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif($i - 0.5 <= $chef->average_rating)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-muted">{{ $chef->ratings_count }} تقييم</p>
                            </div>
                            
                            <div class="rating-bars">
                                @for($i = 5; $i >= 1; $i--)
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2">{{ $i }} <i class="fas fa-star text-warning"></i></div>
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            @php
                                                $percentage = $chef->ratings_count > 0 ? ($chef->ratings_distribution[$i] ?? 0) / $chef->ratings_count * 100 : 0;
                                            @endphp
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="ms-2 small">{{ $chef->ratings_distribution[$i] ?? 0 }}</div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="col-md-8">
                            @if(count($reviews) > 0)
                                @foreach($reviews as $review)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $review->customer->user->profile_image ?? '/images/default-user.jpg' }}" class="rounded-circle me-2" alt="{{ $review->customer->user->name }}" width="40" height="40">
                                                    <div>
                                                        <h6 class="mb-0">{{ $review->customer->user->name }}</h6>
                                                        <small class="text-muted">{{ $review->created_at->format('Y/m/d') }}</small>
                                                    </div>
                                                </div>
                                                <div class="rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="mb-1">{{ $review->comment }}</p>
                                            @if($review->dish)
                                                <small class="text-muted">
                                                    <i class="fas fa-utensils me-1"></i> طلب: {{ $review->dish->name }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                
                                <!-- الترقيم -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $reviews->links() }}
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> لا توجد تقييمات بعد لهذا الشيف.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال المراسلة -->
@auth
    @if(Auth::user()->type === 'customer')
        <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="messageModalLabel">مراسلة {{ $chef->user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                    <form action="{{ route('frontend.messages.send') }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $chef->user->id }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="subject" class="form-label">الموضوع</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">الرسالة</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary">إرسال</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endauth
@endsection

@section('styles')
<style>
    .chef-avatar {
        position: absolute;
        top: 190px;
        left: 50px;
    }
    
    @media (max-width: 768px) {
        .chef-avatar {
            left: 50%;
            transform: translateX(-50%);
        }
    }
    
    .dish-card {
        transition: all 0.3s ease;
    }
    
    .dish-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endsection
