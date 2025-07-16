@extends('frontend.layouts.app')

@section('title', 'إدارة الأطباق')

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
    
    <!-- محتوى إدارة الأطباق -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>إدارة الأطباق</h2>
            <a href="{{ route('frontend.chef.dishes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> إضافة طبق جديد
            </a>
        </div>
        
        <!-- فلتر البحث -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('frontend.chef.dishes') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">البحث</label>
                            <input type="text" class="form-control" id="search" name="search" placeholder="ابحث عن طبق..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="category" class="form-label">التصنيف</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">كل التصنيفات</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">الحالة</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">الكل</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>متاح</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير متاح</option>
                                <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>مميز</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="d-grid w-100">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i> بحث
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- قائمة الأطباق -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                @if(count($dishes) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" width="80">الصورة</th>
                                    <th scope="col">اسم الطبق</th>
                                    <th scope="col">التصنيف</th>
                                    <th scope="col">السعر</th>
                                    <th scope="col">الحالة</th>
                                    <th scope="col">الطلبات</th>
                                    <th scope="col">التقييم</th>
                                    <th scope="col" width="180">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dishes as $dish)
                                    <tr>
                                        <td>
                                            <img src="{{ $dish->image_url }}" alt="{{ $dish->name }}" class="img-thumbnail" width="60" height="60" style="object-fit: cover;">
                                        </td>
                                        <td>
                                            <strong>{{ $dish->name }}</strong>
                                            @if($dish->is_featured)
                                                <span class="badge bg-warning text-dark ms-1">مميز</span>
                                            @endif
                                        </td>
                                        <td>{{ $dish->category->name }}</td>
                                        <td>{{ $dish->price }} ريال</td>
                                        <td>
                                            @if($dish->is_active)
                                                <span class="badge bg-success">متاح</span>
                                            @else
                                                <span class="badge bg-danger">غير متاح</span>
                                            @endif
                                        </td>
                                        <td>{{ $dish->orders_count }}</td>
                                        <td>
                                            <div class="rating">
                                                @php $avgRating = $dish->ratings->avg('rating') ?? 0; @endphp
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $avgRating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @elseif($i - 0.5 <= $avgRating)
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-1">({{ $dish->ratings_count }})</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('frontend.dishes.show', $dish->id) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('frontend.chef.dishes.edit', $dish->id) }}" class="btn btn-sm btn-outline-secondary" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-success toggle-status-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#toggleStatusModal" 
                                                        data-dish-id="{{ $dish->id }}" 
                                                        data-dish-name="{{ $dish->name }}" 
                                                        data-dish-status="{{ $dish->is_active ? 'active' : 'inactive' }}"
                                                        title="{{ $dish->is_active ? 'إيقاف' : 'تفعيل' }}">
                                                    <i class="fas {{ $dish->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning toggle-featured-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#toggleFeaturedModal" 
                                                        data-dish-id="{{ $dish->id }}" 
                                                        data-dish-name="{{ $dish->name }}" 
                                                        data-dish-featured="{{ $dish->is_featured ? 'yes' : 'no' }}"
                                                        title="{{ $dish->is_featured ? 'إلغاء التمييز' : 'تمييز' }}">
                                                    <i class="fas {{ $dish->is_featured ? 'fa-star' : 'fa-star text-muted' }}"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-dish-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteDishModal" 
                                                        data-dish-id="{{ $dish->id }}" 
                                                        data-dish-name="{{ $dish->name }}"
                                                        title="حذف">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- الترقيم -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $dishes->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="alert alert-info m-3">
                        <i class="fas fa-info-circle me-2"></i> لم يتم العثور على أطباق. <a href="{{ route('frontend.chef.dishes.create') }}" class="alert-link">أضف طبقك الأول</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- مودال تغيير حالة الطبق -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="toggleStatusModalLabel">تغيير حالة الطبق</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <p id="toggleStatusMessage">هل أنت متأكد من تغيير حالة الطبق "<span id="dishNameStatus"></span>"؟</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="toggleStatusForm" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">تأكيد</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- مودال تغيير حالة التمييز -->
<div class="modal fade" id="toggleFeaturedModal" tabindex="-1" aria-labelledby="toggleFeaturedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="toggleFeaturedModalLabel">تغيير حالة التمييز</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <p id="toggleFeaturedMessage">هل أنت متأكد من تغيير حالة تمييز الطبق "<span id="dishNameFeatured"></span>"؟</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="toggleFeaturedForm" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning">تأكيد</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- مودال حذف الطبق -->
<div class="modal fade" id="deleteDishModal" tabindex="-1" aria-labelledby="deleteDishModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDishModalLabel">حذف الطبق</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i> تحذير: هذا الإجراء لا يمكن التراجع عنه!
                </div>
                <p>هل أنت متأكد من حذف الطبق "<span id="dishNameDelete"></span>"؟</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteDishForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تغيير حالة الطبق
        const toggleStatusBtns = document.querySelectorAll('.toggle-status-btn');
        toggleStatusBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const dishId = this.getAttribute('data-dish-id');
                const dishName = this.getAttribute('data-dish-name');
                const dishStatus = this.getAttribute('data-dish-status');
                
                document.getElementById('dishNameStatus').textContent = dishName;
                document.getElementById('toggleStatusMessage').innerHTML = dishStatus === 'active' 
                    ? `هل أنت متأكد من إيقاف الطبق "<span id="dishNameStatus">${dishName}</span>"؟ لن يتمكن العملاء من طلبه.`
                    : `هل أنت متأكد من تفعيل الطبق "<span id="dishNameStatus">${dishName}</span>"؟ سيتمكن العملاء من طلبه.`;
                
                document.getElementById('toggleStatusForm').action = `/chef/dishes/${dishId}/toggle-status`;
            });
        });
        
        // تغيير حالة التمييز
        const toggleFeaturedBtns = document.querySelectorAll('.toggle-featured-btn');
        toggleFeaturedBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const dishId = this.getAttribute('data-dish-id');
                const dishName = this.getAttribute('data-dish-name');
                const dishFeatured = this.getAttribute('data-dish-featured');
                
                document.getElementById('dishNameFeatured').textContent = dishName;
                document.getElementById('toggleFeaturedMessage').innerHTML = dishFeatured === 'yes' 
                    ? `هل أنت متأكد من إلغاء تمييز الطبق "<span id="dishNameFeatured">${dishName}</span>"؟`
                    : `هل أنت متأكد من تمييز الطبق "<span id="dishNameFeatured">${dishName}</span>"؟ سيظهر في قسم الأطباق المميزة.`;
                
                document.getElementById('toggleFeaturedForm').action = `/chef/dishes/${dishId}/toggle-featured`;
            });
        });
        
        // حذف الطبق
        const deleteDishBtns = document.querySelectorAll('.delete-dish-btn');
        deleteDishBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const dishId = this.getAttribute('data-dish-id');
                const dishName = this.getAttribute('data-dish-name');
                
                document.getElementById('dishNameDelete').textContent = dishName;
                document.getElementById('deleteDishForm').action = `/chef/dishes/${dishId}`;
            });
        });
    });
</script>
@endsection
