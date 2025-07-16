@extends('frontend.layouts.app')

@section('title', 'تعديل العنوان')

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
    
    <!-- محتوى تعديل العنوان -->
    <div class="col-lg-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">تعديل العنوان</h4>
                <a href="{{ route('frontend.customer.addresses') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right me-2"></i> العودة للعناوين
                </a>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                
                <form action="{{ route('frontend.customer.addresses.update', $address->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">عنوان مختصر <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $address->title) }}" placeholder="مثال: المنزل، العمل، منزل العائلة" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="recipient_name" class="form-label">اسم المستلم <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('recipient_name') is-invalid @enderror" id="recipient_name" name="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" required>
                            @error('recipient_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $address->phone) }}" placeholder="05xxxxxxxx" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">المدينة <span class="text-danger">*</span></label>
                            <select class="form-select @error('city') is-invalid @enderror" id="city" name="city" required>
                                <option value="" disabled>اختر المدينة</option>
                                <option value="الرياض" {{ old('city', $address->city) == 'الرياض' ? 'selected' : '' }}>الرياض</option>
                                <option value="جدة" {{ old('city', $address->city) == 'جدة' ? 'selected' : '' }}>جدة</option>
                                <option value="مكة المكرمة" {{ old('city', $address->city) == 'مكة المكرمة' ? 'selected' : '' }}>مكة المكرمة</option>
                                <option value="المدينة المنورة" {{ old('city', $address->city) == 'المدينة المنورة' ? 'selected' : '' }}>المدينة المنورة</option>
                                <option value="الدمام" {{ old('city', $address->city) == 'الدمام' ? 'selected' : '' }}>الدمام</option>
                                <option value="الخبر" {{ old('city', $address->city) == 'الخبر' ? 'selected' : '' }}>الخبر</option>
                                <option value="الظهران" {{ old('city', $address->city) == 'الظهران' ? 'selected' : '' }}>الظهران</option>
                                <option value="أبها" {{ old('city', $address->city) == 'أبها' ? 'selected' : '' }}>أبها</option>
                                <option value="الطائف" {{ old('city', $address->city) == 'الطائف' ? 'selected' : '' }}>الطائف</option>
                                <option value="تبوك" {{ old('city', $address->city) == 'تبوك' ? 'selected' : '' }}>تبوك</option>
                                <option value="القصيم" {{ old('city', $address->city) == 'القصيم' ? 'selected' : '' }}>القصيم</option>
                                <option value="حائل" {{ old('city', $address->city) == 'حائل' ? 'selected' : '' }}>حائل</option>
                                <option value="نجران" {{ old('city', $address->city) == 'نجران' ? 'selected' : '' }}>نجران</option>
                                <option value="جازان" {{ old('city', $address->city) == 'جازان' ? 'selected' : '' }}>جازان</option>
                                <option value="الباحة" {{ old('city', $address->city) == 'الباحة' ? 'selected' : '' }}>الباحة</option>
                                <option value="الجوف" {{ old('city', $address->city) == 'الجوف' ? 'selected' : '' }}>الجوف</option>
                            </select>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="area" class="form-label">الحي <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('area') is-invalid @enderror" id="area" name="area" value="{{ old('area', $address->area) }}" required>
                            @error('area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="street" class="form-label">الشارع <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" value="{{ old('street', $address->street) }}" required>
                            @error('street')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="building_no" class="form-label">رقم المبنى <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('building_no') is-invalid @enderror" id="building_no" name="building_no" value="{{ old('building_no', $address->building_no) }}" required>
                            @error('building_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="apartment_no" class="form-label">رقم الشقة</label>
                            <input type="text" class="form-control @error('apartment_no') is-invalid @enderror" id="apartment_no" name="apartment_no" value="{{ old('apartment_no', $address->apartment_no) }}">
                            @error('apartment_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="postal_code" class="form-label">الرمز البريدي</label>
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}">
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label for="landmark" class="form-label">علامة مميزة</label>
                            <textarea class="form-control @error('landmark') is-invalid @enderror" id="landmark" name="landmark" rows="2" placeholder="مثال: بجوار مسجد، مقابل مدرسة، خلف مركز تجاري">{{ old('landmark', $address->landmark) }}</textarea>
                            @error('landmark')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @if(!$address->is_default)
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input @error('is_default') is-invalid @enderror" type="checkbox" id="is_default" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_default">
                                        تعيين كعنوان افتراضي
                                    </label>
                                    @error('is_default')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <div class="col-md-12 mb-3">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> هذا هو العنوان الافتراضي الحالي.
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                        <a href="{{ route('frontend.customer.addresses') }}" class="btn btn-outline-secondary me-md-2">إلغاء</a>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
