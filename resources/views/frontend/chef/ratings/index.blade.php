@extends('frontend.layouts.app')

@section('title', 'إدارة التقييمات')

@section('content')
<div class="row">
    <!-- القائمة الجانبية -->
    <div class="col-lg-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <img src="{{ Auth::user()->profile_image ?? '/images/default-chef.jpg' }}" class="rounded-circle mb-3" alt="{{ Auth::user()->name }}" width="100" height="100" style="object-fit: cover;">
                <h4>{{ Auth::user()->name }}</h4>
                <p class="text-muted">
                    <span class="badge bg-success">شيف</span>
                </p>
                <p class="text-muted">{{ Auth::user()->email }}</p>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('frontend.chef.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('frontend.chef.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> لوحة التحكم
                </a>
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
    
    <!-- محتوى إدارة التقييمات -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>إدارة التقييمات</h2>
        </div>
        
        <!-- ملخص التقييمات -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="display-4 fw-bold text-primary">{{ number_format($averageRating, 1) }}</div>
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $averageRating)
                                    <i class="fas fa-star text-warning"></i>
                                @elseif($i - 0.5 <= $averageRating)
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-muted">{{ $totalRatings }} تقييم</p>
                    </div>
                    <div class="col-md-8">
                        <div class="rating-bars">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2">5 <i class="fas fa-star text-warning"></i></div>
                                <div class="progress flex-grow-1" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($ratingCounts[5] / $totalRatings) * 100 }}%"></div>
                                </div>
                                <div class="ms-2">{{ $ratingCounts[5] }}</div>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2">4 <i class="fas fa-star text-warning"></i></div>
                                <div class="progress flex-grow-1" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($ratingCounts[4] / $totalRatings) * 100 }}%"></div>
                                </div>
                                <div class="ms-2">{{ $ratingCounts[4] }}</div>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2">3 <i class="fas fa-star text-warning"></i></div>
                                <div class="progress flex-grow-1" style="height: 10px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ ($ratingCounts[3] / $totalRatings) * 100 }}%"></div>
                                </div>
                                <div class="ms-2">{{ $ratingCounts[3] }}</div>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2">2 <i class="fas fa-star text-warning"></i></div>
                                <div class="progress flex-grow-1" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ ($ratingCounts[2] / $totalRatings) * 100 }}%"></div>
                                </div>
                                <div class="ms-2">{{ $ratingCounts[2] }}</div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="me-2">1 <i class="fas fa-star text-warning"></i></div>
                                <div class="progress flex-grow-1" style="height: 10px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($ratingCounts[1] / $totalRatings) * 100 }}%"></div>
                                </div>
                                <div class="ms-2">{{ $ratingCounts[1] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- تبويبات التقييمات -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white p-0">
                <ul class="nav nav-tabs" id="ratingTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">
                            كل التقييمات <span class="badge bg-secondary ms-1">{{ $totalRatings }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="positive-tab" data-bs-toggle="tab" data-bs-target="#positive" type="button" role="tab" aria-controls="positive" aria-selected="false">
                            إيجابية <span class="badge bg-success ms-1">{{ $ratingCounts[5] + $ratingCounts[4] }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="neutral-tab" data-bs-toggle="tab" data-bs-target="#neutral" type="button" role="tab" aria-controls="neutral" aria-selected="false">
                            محايدة <span class="badge bg-info ms-1">{{ $ratingCounts[3] }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="negative-tab" data-bs-toggle="tab" data-bs-target="#negative" type="button" role="tab" aria-controls="negative" aria-selected="false">
                            سلبية <span class="badge bg-danger ms-1">{{ $ratingCounts[2] + $ratingCounts[1] }}</span>
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="ratingTabsContent">
                    <!-- كل التقييمات -->
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        @include('frontend.chef.ratings.partials.ratings-list', ['ratings' => $allRatings])
                    </div>
                    
                    <!-- التقييمات الإيجابية -->
                    <div class="tab-pane fade" id="positive" role="tabpanel" aria-labelledby="positive-tab">
                        @include('frontend.chef.ratings.partials.ratings-list', ['ratings' => $positiveRatings])
                    </div>
                    
                    <!-- التقييمات المحايدة -->
                    <div class="tab-pane fade" id="neutral" role="tabpanel" aria-labelledby="neutral-tab">
                        @include('frontend.chef.ratings.partials.ratings-list', ['ratings' => $neutralRatings])
                    </div>
                    
                    <!-- التقييمات السلبية -->
                    <div class="tab-pane fade" id="negative" role="tabpanel" aria-labelledby="negative-tab">
                        @include('frontend.chef.ratings.partials.ratings-list', ['ratings' => $negativeRatings])
                    </div>
                </div>
            </div>
        </div>
        
        <!-- نصائح لتحسين التقييمات -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">نصائح لتحسين التقييمات</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <div class="me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div>
                                <h6>الالتزام بوقت التحضير</h6>
                                <p class="text-muted">احرص على تحضير الطلبات في الوقت المحدد لتجنب تأخير التوصيل للعملاء.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <div class="me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            </div>
                            <div>
                                <h6>جودة الطعام</h6>
                                <p class="text-muted">استخدم مكونات طازجة وعالية الجودة واحرص على تقديم أطباق متسقة في الطعم والمظهر.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <div class="me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-comment-alt"></i>
                                </div>
                            </div>
                            <div>
                                <h6>التواصل مع العملاء</h6>
                                <p class="text-muted">تفاعل مع العملاء وردودهم، وتعامل مع الشكاوى بشكل احترافي وسريع.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <div class="me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>
                            <div>
                                <h6>التغليف والتقديم</h6>
                                <p class="text-muted">استخدم عبوات نظيفة وآمنة، واهتم بمظهر الطعام حتى عند التوصيل.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال الرد على التقييم -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyModalLabel">الرد على التقييم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form id="reply-form" action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="reply_text" class="form-label">نص الرد</label>
                        <textarea class="form-control" id="reply_text" name="reply" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="submit-reply">إرسال الرد</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // إعداد مودال الرد على التقييم
        const replyModal = document.getElementById('replyModal');
        const replyForm = document.getElementById('reply-form');
        const replyButtons = document.querySelectorAll('.reply-btn');
        
        replyButtons.forEach(button => {
            button.addEventListener('click', function() {
                const ratingId = this.getAttribute('data-rating-id');
                replyForm.action = `/chef/ratings/${ratingId}/reply`;
                
                // إذا كان هناك رد سابق، قم بتعبئته في النموذج
                const existingReply = this.getAttribute('data-existing-reply');
                if (existingReply) {
                    document.getElementById('reply_text').value = existingReply;
                } else {
                    document.getElementById('reply_text').value = '';
                }
            });
        });
        
        // إرسال نموذج الرد
        document.getElementById('submit-reply').addEventListener('click', function() {
            replyForm.submit();
        });
    });
</script>
@endsection
