@extends('frontend.layouts.app')

@section('title', 'عناويني')

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
    
    <!-- محتوى العناوين -->
    <div class="col-lg-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">عناويني</h4>
                <a href="{{ route('frontend.customer.addresses.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i> إضافة عنوان جديد
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(count($addresses) > 0)
                    <div class="row">
                        @foreach($addresses as $address)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 {{ $address->is_default ? 'border-primary' : '' }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title mb-0">{{ $address->title }}</h5>
                                            @if($address->is_default)
                                                <span class="badge bg-primary">العنوان الافتراضي</span>
                                            @endif
                                        </div>
                                        
                                        <p class="card-text">
                                            <i class="fas fa-user me-2"></i> {{ $address->recipient_name }}
                                        </p>
                                        <p class="card-text">
                                            <i class="fas fa-phone me-2"></i> {{ $address->phone }}
                                        </p>
                                        <p class="card-text">
                                            <i class="fas fa-map-marker-alt me-2"></i> {{ $address->city }}, {{ $address->area }}
                                        </p>
                                        <p class="card-text">
                                            <i class="fas fa-road me-2"></i> {{ $address->street }}, {{ $address->building_no }}
                                        </p>
                                        @if($address->apartment_no)
                                            <p class="card-text">
                                                <i class="fas fa-home me-2"></i> شقة {{ $address->apartment_no }}
                                            </p>
                                        @endif
                                        @if($address->landmark)
                                            <p class="card-text">
                                                <i class="fas fa-info-circle me-2"></i> {{ $address->landmark }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="card-footer bg-white">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                @if(!$address->is_default)
                                                    <form action="{{ route('frontend.customer.addresses.default', $address->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-check-circle me-1"></i> تعيين كافتراضي
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('frontend.customer.addresses.edit', $address->id) }}" class="btn btn-sm btn-outline-secondary me-1">
                                                    <i class="fas fa-edit"></i> تعديل
                                                </a>
                                                @if(!$address->is_default)
                                                    <form action="{{ route('frontend.customer.addresses.destroy', $address->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash-alt"></i> حذف
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> لم تقم بإضافة أي عناوين حتى الآن.
                        <a href="{{ route('frontend.customer.addresses.create') }}" class="alert-link">أضف عنوانك الأول</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تأكيد حذف العنوان
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('هل أنت متأكد من رغبتك في حذف هذا العنوان؟')) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection
