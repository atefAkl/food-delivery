@extends('frontend.layouts.app')

@section('title', 'إدارة الطلبات')

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
    
    <!-- محتوى إدارة الطلبات -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>إدارة الطلبات</h2>
            <div class="d-flex">
                <div class="dropdown me-2">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-download me-2"></i> تصدير
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                        <li><a class="dropdown-item" href="{{ route('frontend.chef.orders.export', ['format' => 'pdf']) }}">PDF</a></li>
                        <li><a class="dropdown-item" href="{{ route('frontend.chef.orders.export', ['format' => 'excel']) }}">Excel</a></li>
                    </ul>
                </div>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter me-2"></i> فلترة متقدمة
                </button>
            </div>
        </div>
        
        <!-- بطاقات الإحصائيات -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">إجمالي الطلبات</h6>
                                <h2 class="mb-0 mt-2">{{ $totalOrders }}</h2>
                            </div>
                            <i class="fas fa-shopping-bag fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">طلبات مكتملة</h6>
                                <h2 class="mb-0 mt-2">{{ $completedOrders }}</h2>
                            </div>
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-dark h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">قيد التحضير</h6>
                                <h2 class="mb-0 mt-2">{{ $processingOrders }}</h2>
                            </div>
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">طلبات ملغاة</h6>
                                <h2 class="mb-0 mt-2">{{ $cancelledOrders }}</h2>
                            </div>
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- تبويبات حالة الطلبات -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white p-0">
                <ul class="nav nav-tabs" id="orderTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">
                            الكل <span class="badge bg-secondary ms-1">{{ $totalOrders }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false">
                            جديدة <span class="badge bg-primary ms-1">{{ $pendingOrders }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="processing-tab" data-bs-toggle="tab" data-bs-target="#processing" type="button" role="tab" aria-controls="processing" aria-selected="false">
                            قيد التحضير <span class="badge bg-warning ms-1">{{ $processingOrders }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="ready-tab" data-bs-toggle="tab" data-bs-target="#ready" type="button" role="tab" aria-controls="ready" aria-selected="false">
                            جاهزة للتسليم <span class="badge bg-info ms-1">{{ $readyOrders }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">
                            مكتملة <span class="badge bg-success ms-1">{{ $completedOrders }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled" aria-selected="false">
                            ملغاة <span class="badge bg-danger ms-1">{{ $cancelledOrders }}</span>
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-0">
                <div class="tab-content" id="orderTabsContent">
                    <!-- جميع الطلبات -->
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        @include('frontend.chef.orders.partials.orders-table', ['orders' => $orders])
                    </div>
                    
                    <!-- الطلبات الجديدة -->
                    <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                        @include('frontend.chef.orders.partials.orders-table', ['orders' => $pendingOrdersList])
                    </div>
                    
                    <!-- الطلبات قيد التحضير -->
                    <div class="tab-pane fade" id="processing" role="tabpanel" aria-labelledby="processing-tab">
                        @include('frontend.chef.orders.partials.orders-table', ['orders' => $processingOrdersList])
                    </div>
                    
                    <!-- الطلبات الجاهزة للتسليم -->
                    <div class="tab-pane fade" id="ready" role="tabpanel" aria-labelledby="ready-tab">
                        @include('frontend.chef.orders.partials.orders-table', ['orders' => $readyOrdersList])
                    </div>
                    
                    <!-- الطلبات المكتملة -->
                    <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                        @include('frontend.chef.orders.partials.orders-table', ['orders' => $completedOrdersList])
                    </div>
                    
                    <!-- الطلبات الملغاة -->
                    <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                        @include('frontend.chef.orders.partials.orders-table', ['orders' => $cancelledOrdersList])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال الفلترة المتقدمة -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">فلترة متقدمة للطلبات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('frontend.chef.orders') }}" method="GET" id="filter-form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="order_number" class="form-label">رقم الطلب</label>
                            <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="customer_name" class="form-label">اسم العميل</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ request('customer_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="date_from" class="form-label">من تاريخ</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="date_to" class="form-label">إلى تاريخ</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">حالة الطلب</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">كل الحالات</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>جديد</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد التحضير</option>
                                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>جاهز للتسليم</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="payment_status" class="form-label">حالة الدفع</label>
                            <select class="form-select" id="payment_status" name="payment_status">
                                <option value="">كل الحالات</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>غير مدفوع</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="min_total" class="form-label">الحد الأدنى للمبلغ</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control" id="min_total" name="min_total" value="{{ request('min_total') }}">
                                <span class="input-group-text">ريال</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="max_total" class="form-label">الحد الأقصى للمبلغ</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control" id="max_total" name="max_total" value="{{ request('max_total') }}">
                                <span class="input-group-text">ريال</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" id="reset-filter">إعادة تعيين</button>
                <button type="button" class="btn btn-primary" id="apply-filter">تطبيق الفلتر</button>
            </div>
        </div>
    </div>
</div>

<!-- مودال تغيير حالة الطلب -->
<div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeStatusModalLabel">تغيير حالة الطلب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form id="change-status-form" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="order_status" class="form-label">حالة الطلب</label>
                        <select class="form-select" id="order_status" name="status" required>
                            <option value="pending">جديد</option>
                            <option value="processing">قيد التحضير</option>
                            <option value="ready">جاهز للتسليم</option>
                            <option value="completed">مكتمل</option>
                            <option value="cancelled">ملغي</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status_note" class="form-label">ملاحظات (اختياري)</label>
                        <textarea class="form-control" id="status_note" name="note" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="submit-status-change">حفظ التغييرات</button>
            </div>
        </div>
    </div>
</div>

<!-- مودال تفاصيل الطلب -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">تفاصيل الطلب #<span id="order-number"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body" id="order-details-content">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                    <p class="mt-2">جاري تحميل تفاصيل الطلب...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-primary" id="print-order">
                    <i class="fas fa-print me-2"></i> طباعة
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تطبيق الفلتر
        document.getElementById('apply-filter').addEventListener('click', function() {
            document.getElementById('filter-form').submit();
        });
        
        // إعادة تعيين الفلتر
        document.getElementById('reset-filter').addEventListener('click', function() {
            const form = document.getElementById('filter-form');
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.value = '';
            });
            form.submit();
        });
        
        // تغيير حالة الطلب
        const changeStatusBtns = document.querySelectorAll('.change-status-btn');
        changeStatusBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                const currentStatus = this.getAttribute('data-status');
                
                document.getElementById('change-status-form').action = `/chef/orders/${orderId}/status`;
                
                // تحديد الحالة الحالية في القائمة المنسدلة
                const statusSelect = document.getElementById('order_status');
                for (let i = 0; i < statusSelect.options.length; i++) {
                    if (statusSelect.options[i].value === currentStatus) {
                        statusSelect.selectedIndex = i;
                        break;
                    }
                }
            });
        });
        
        // إرسال نموذج تغيير الحالة
        document.getElementById('submit-status-change').addEventListener('click', function() {
            document.getElementById('change-status-form').submit();
        });
        
        // عرض تفاصيل الطلب
        const viewDetailsBtns = document.querySelectorAll('.view-details-btn');
        viewDetailsBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                const orderNumber = this.getAttribute('data-order-number');
                
                document.getElementById('order-number').textContent = orderNumber;
                
                // إعادة تعيين محتوى المودال وإظهار مؤشر التحميل
                document.getElementById('order-details-content').innerHTML = `
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">جاري التحميل...</span>
                        </div>
                        <p class="mt-2">جاري تحميل تفاصيل الطلب...</p>
                    </div>
                `;
                
                // طلب تفاصيل الطلب من الخادم
                fetch(`/chef/orders/${orderId}/details`)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('order-details-content').innerHTML = html;
                    })
                    .catch(error => {
                        document.getElementById('order-details-content').innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i> حدث خطأ أثناء تحميل تفاصيل الطلب.
                            </div>
                        `;
                        console.error('Error fetching order details:', error);
                    });
            });
        });
        
        // طباعة تفاصيل الطلب
        document.getElementById('print-order').addEventListener('click', function() {
            const printContents = document.getElementById('order-details-content').innerHTML;
            const originalContents = document.body.innerHTML;
            
            document.body.innerHTML = `
                <div class="container mt-4">
                    <div class="text-center mb-4">
                        <h2>تفاصيل الطلب #${document.getElementById('order-number').textContent}</h2>
                        <p>تاريخ الطباعة: ${new Date().toLocaleDateString('ar-SA')}</p>
                    </div>
                    ${printContents}
                </div>
            `;
            
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        });
    });
</script>
@endsection
