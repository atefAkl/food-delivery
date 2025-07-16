@extends('frontend.layouts.app')

@section('title', 'تعديل الملف الشخصي')

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
    
    <!-- محتوى تعديل الملف الشخصي -->
    <div class="col-lg-9">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0">تعديل الملف الشخصي</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('frontend.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <img src="{{ Auth::user()->profile_image ?? '/images/default-user.jpg' }}" class="rounded-circle mb-3 profile-preview" alt="{{ Auth::user()->name }}" width="150" height="150" style="object-fit: cover;">
                                <div class="d-grid">
                                    <label for="profile_image" class="btn btn-outline-primary">
                                        <i class="fas fa-camera me-2"></i> تغيير الصورة
                                    </label>
                                    <input type="file" id="profile_image" name="profile_image" class="d-none" accept="image/*" onchange="previewImage(this)">
                                </div>
                                @error('profile_image')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-9">
                            <div class="mb-3">
                                <label for="name" class="form-label">الاسم الكامل</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control" id="email" value="{{ Auth::user()->email }}" disabled>
                                <small class="text-muted">لا يمكن تغيير البريد الإلكتروني</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="bio" class="form-label">نبذة شخصية</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="4">{{ old('bio', Auth::user()->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    @if(Auth::user()->type === 'chef')
                        <hr>
                        <h5 class="mb-3">معلومات الشيف</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="speciality" class="form-label">التخصص</label>
                                <input type="text" class="form-control @error('speciality') is-invalid @enderror" id="speciality" name="speciality" value="{{ old('speciality', Auth::user()->chef->speciality ?? '') }}">
                                @error('speciality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="experience_years" class="form-label">سنوات الخبرة</label>
                                <input type="number" class="form-control @error('experience_years') is-invalid @enderror" id="experience_years" name="experience_years" value="{{ old('experience_years', Auth::user()->chef->experience_years ?? '') }}">
                                @error('experience_years')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="certificates" class="form-label">الشهادات والمؤهلات</label>
                                <textarea class="form-control @error('certificates') is-invalid @enderror" id="certificates" name="certificates" rows="3">{{ old('certificates', Auth::user()->chef->certificates ?? '') }}</textarea>
                                @error('certificates')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('frontend.profile') }}" class="btn btn-outline-secondary me-md-2">إلغاء</a>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.querySelector('.profile-preview').setAttribute('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
