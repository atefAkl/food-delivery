@extends('frontend.layouts.app')

@section('title', 'تغيير كلمة المرور')

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
    
    <!-- محتوى تغيير كلمة المرور -->
    <div class="col-lg-9">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0">تغيير كلمة المرور</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('frontend.profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">كلمة المرور الجديدة</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">يجب أن تتكون كلمة المرور من 8 أحرف على الأقل وتحتوي على حرف كبير وحرف صغير ورقم ورمز خاص.</div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> تنبيه: بعد تغيير كلمة المرور، سيتم تسجيل خروجك تلقائيًا وستحتاج إلى تسجيل الدخول مرة أخرى باستخدام كلمة المرور الجديدة.
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('frontend.profile') }}" class="btn btn-outline-secondary me-md-2">إلغاء</a>
                        <button type="submit" class="btn btn-primary">تغيير كلمة المرور</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
