@extends('frontend.layouts.app')

@section('title', 'إنشاء حساب جديد')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">إنشاء حساب جديد</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('frontend.register.submit') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">الاسم الكامل</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">رقم الهاتف</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">نوع الحساب</label>
                        <div class="row">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <input type="radio" class="btn-check" name="type" id="customer" value="customer" {{ old('type') == 'customer' ? 'checked' : '' }} required>
                                        <label class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center" for="customer">
                                            <i class="fas fa-user fa-3x mb-3"></i>
                                            <h5>عميل</h5>
                                            <p class="mb-0 text-muted">اطلب الطعام من أفضل الشيفات</p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <input type="radio" class="btn-check" name="type" id="chef" value="chef" {{ old('type') == 'chef' ? 'checked' : '' }} required>
                                        <label class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center" for="chef">
                                            <i class="fas fa-utensils fa-3x mb-3"></i>
                                            <h5>شيف</h5>
                                            <p class="mb-0 text-muted">قم بإعداد وبيع أطباقك المميزة</p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('type')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">إنشاء حساب</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">لديك حساب بالفعل؟ <a href="{{ route('frontend.login') }}" class="text-decoration-none">تسجيل الدخول</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
