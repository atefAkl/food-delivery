@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 400px; margin: 60px auto">
    <div class="card shadow-sm">
        <div class="card-header text-center bg-primary text-white">
            <h4>تسجيل دخول المدير</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">كلمة المرور</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">تذكرني</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">دخول</button>
            </form>
        </div>
    </div>
</div>
@endsection
