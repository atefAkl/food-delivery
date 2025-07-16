@extends('frontend.layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">تسجيل الدخول</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('frontend.login.submit') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
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
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">تذكرني</label>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">تسجيل الدخول</button>
                    </div>
                    
                    <div class="mt-3 text-center">
                        <a href="{{ route('frontend.password.request') }}" class="text-decoration-none">نسيت كلمة المرور؟</a>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">ليس لديك حساب؟ <a href="{{ route('frontend.register') }}" class="text-decoration-none">إنشاء حساب جديد</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
