@extends('frontend.layouts.app')

@section('title', 'إدارة الأرباح')

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
    
    <!-- محتوى إدارة الأرباح -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>إدارة الأرباح</h2>
            <div class="d-flex">
                <div class="dropdown me-2">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-download me-2"></i> تصدير
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                        <li><a class="dropdown-item" href="{{ route('frontend.chef.earnings.export', ['format' => 'pdf']) }}">PDF</a></li>
                        <li><a class="dropdown-item" href="{{ route('frontend.chef.earnings.export', ['format' => 'excel']) }}">Excel</a></li>
                    </ul>
                </div>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter me-2"></i> فلترة متقدمة
                </button>
            </div>
        </div>
        
        <!-- بطاقات الإحصائيات -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">إجمالي الأرباح</h6>
                                <h2 class="mb-0 mt-2">{{ number_format($totalEarnings, 2) }} ريال</h2>
                            </div>
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">أرباح هذا الشهر</h6>
                                <h2 class="mb-0 mt-2">{{ number_format($monthlyEarnings, 2) }} ريال</h2>
                            </div>
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">متوسط الأرباح اليومية</h6>
                                <h2 class="mb-0 mt-2">{{ number_format($averageDailyEarnings, 2) }} ريال</h2>
                            </div>
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- رسم بياني للأرباح -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">تحليل الأرباح</h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-secondary active" id="weekly-chart">أسبوعي</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="monthly-chart">شهري</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="yearly-chart">سنوي</button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="earningsChart" height="300"></canvas>
            </div>
        </div>
        
        <!-- جدول المعاملات المالية -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">سجل المعاملات المالية</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">رقم المعاملة</th>
                                <th scope="col">التاريخ</th>
                                <th scope="col">رقم الطلب</th>
                                <th scope="col">المبلغ</th>
                                <th scope="col">عمولة المنصة</th>
                                <th scope="col">صافي الربح</th>
                                <th scope="col">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>
                                        <strong>#{{ $transaction->transaction_id }}</strong>
                                    </td>
                                    <td>
                                        <div>{{ $transaction->created_at->format('Y/m/d') }}</div>
                                        <small class="text-muted">{{ $transaction->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('frontend.chef.orders.show', $transaction->order_id) }}">#{{ $transaction->order->order_number }}</a>
                                    </td>
                                    <td>{{ number_format($transaction->amount, 2) }} ريال</td>
                                    <td>{{ number_format($transaction->platform_fee, 2) }} ريال</td>
                                    <td><strong>{{ number_format($transaction->net_amount, 2) }} ريال</strong></td>
                                    <td>
                                        @if($transaction->status == 'completed')
                                            <span class="badge bg-success">مكتملة</span>
                                        @elseif($transaction->status == 'pending')
                                            <span class="badge bg-warning text-dark">قيد المعالجة</span>
                                        @elseif($transaction->status == 'failed')
                                            <span class="badge bg-danger">فشلت</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $transaction->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted mb-0">لا توجد معاملات مالية حتى الآن.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- الترقيم -->
                <div class="d-flex justify-content-center p-3">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
        <!-- معلومات الدفع -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">معلومات الدفع</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#paymentInfoModal">
                    <i class="fas fa-edit me-2"></i> تعديل
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>معلومات الحساب البنكي</h6>
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th class="ps-0">اسم البنك:</th>
                                    <td>{{ $paymentInfo->bank_name ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">اسم صاحب الحساب:</th>
                                    <td>{{ $paymentInfo->account_name ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">رقم الحساب:</th>
                                    <td>{{ $paymentInfo->account_number ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">رقم الآيبان:</th>
                                    <td>{{ $paymentInfo->iban ?? 'غير محدد' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>معلومات إضافية</h6>
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th class="ps-0">عمولة المنصة:</th>
                                    <td>{{ $platformFeePercentage }}%</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">دورة الدفع:</th>
                                    <td>{{ $paymentCycle }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">تاريخ الدفع القادم:</th>
                                    <td>{{ $nextPaymentDate }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">المبلغ المتوقع:</th>
                                    <td>{{ number_format($pendingAmount, 2) }} ريال</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال الفلترة المتقدمة -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">فلترة متقدمة للمعاملات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('frontend.chef.earnings') }}" method="GET" id="filter-form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="date_from" class="form-label">من تاريخ</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="date_to" class="form-label">إلى تاريخ</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">حالة المعاملة</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">كل الحالات</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد المعالجة</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشلت</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="min_amount" class="form-label">الحد الأدنى للمبلغ</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control" id="min_amount" name="min_amount" value="{{ request('min_amount') }}">
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

<!-- مودال تعديل معلومات الدفع -->
<div class="modal fade" id="paymentInfoModal" tabindex="-1" aria-labelledby="paymentInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentInfoModalLabel">تعديل معلومات الدفع</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('frontend.chef.earnings.update-payment-info') }}" method="POST" id="payment-info-form">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="bank_name" class="form-label">اسم البنك</label>
                        <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ $paymentInfo->bank_name ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="account_name" class="form-label">اسم صاحب الحساب</label>
                        <input type="text" class="form-control" id="account_name" name="account_name" value="{{ $paymentInfo->account_name ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="account_number" class="form-label">رقم الحساب</label>
                        <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $paymentInfo->account_number ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="iban" class="form-label">رقم الآيبان</label>
                        <input type="text" class="form-control" id="iban" name="iban" value="{{ $paymentInfo->iban ?? '' }}" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="submit-payment-info">حفظ التغييرات</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        // إرسال نموذج معلومات الدفع
        document.getElementById('submit-payment-info').addEventListener('click', function() {
            document.getElementById('payment-info-form').submit();
        });
        
        // رسم بياني للأرباح
        const ctx = document.getElementById('earningsChart').getContext('2d');
        const earningsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [{
                    label: 'الأرباح',
                    data: {!! json_encode($chartData['data']) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' ريال';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw + ' ريال';
                            }
                        }
                    }
                }
            }
        });
        
        // تبديل الرسم البياني
        document.getElementById('weekly-chart').addEventListener('click', function() {
            updateChart('weekly');
            setActiveButton(this);
        });
        
        document.getElementById('monthly-chart').addEventListener('click', function() {
            updateChart('monthly');
            setActiveButton(this);
        });
        
        document.getElementById('yearly-chart').addEventListener('click', function() {
            updateChart('yearly');
            setActiveButton(this);
        });
        
        function updateChart(period) {
            fetch(`/chef/earnings/chart-data?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    earningsChart.data.labels = data.labels;
                    earningsChart.data.datasets[0].data = data.data;
                    earningsChart.update();
                });
        }
        
        function setActiveButton(button) {
            const buttons = document.querySelectorAll('.btn-group .btn');
            buttons.forEach(btn => {
                btn.classList.remove('active');
            });
            button.classList.add('active');
        }
    });
</script>
@endsection
