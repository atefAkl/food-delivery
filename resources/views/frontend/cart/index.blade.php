@extends('frontend.layouts.app')

@section('title', 'سلة التسوق')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">سلة التسوق</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">سلة التسوق</h2>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(count($cartItems) > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">الأطباق في السلة ({{ count($cartItems) }})</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50%">الطبق</th>
                                        <th>السعر</th>
                                        <th>الكمية</th>
                                        <th>الإجمالي</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item['dish']->image_url }}" alt="{{ $item['dish']->name }}" class="rounded me-3" width="70" height="70" style="object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-0">{{ $item['dish']->name }}</h6>
                                                        <small class="text-muted">{{ $item['dish']->chef->user->name }}</small>
                                                        @if(!empty($item['options']))
                                                            <div class="mt-1">
                                                                @foreach($item['options'] as $key => $value)
                                                                    <span class="badge bg-light text-dark me-1">{{ $key }}: {{ $value }}</span>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                        @if(!empty($item['notes']))
                                                            <div class="mt-1">
                                                                <small class="text-muted">ملاحظات: {{ $item['notes'] }}</small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $item['price'] }} ريال</td>
                                            <td>
                                                <div class="quantity-control d-flex align-items-center">
                                                    <form action="{{ route('frontend.cart.update', $item['id']) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="action" value="decrease">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </form>
                                                    <span class="mx-2">{{ $item['quantity'] }}</span>
                                                    <form action="{{ route('frontend.cart.update', $item['id']) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="action" value="increase">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td>{{ $item['price'] * $item['quantity'] }} ريال</td>
                                            <td>
                                                <form action="{{ route('frontend.cart.remove', $item['id']) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger remove-item">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('frontend.dishes') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-right me-2"></i> مواصلة التسوق
                            </a>
                            <form action="{{ route('frontend.cart.clear') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger clear-cart">
                                    <i class="fas fa-trash-alt me-2"></i> إفراغ السلة
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ملاحظات الطلب -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">ملاحظات الطلب</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('frontend.cart.update-notes') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="order_notes" class="form-label">ملاحظات إضافية للطلب</label>
                                <textarea class="form-control" id="order_notes" name="order_notes" rows="3" placeholder="أضف أي ملاحظات أو تعليمات خاصة بالطلب...">{{ session('order_notes') }}</textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-save me-2"></i> حفظ الملاحظات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- ملخص الطلب -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">ملخص الطلب</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>المجموع الفرعي:</span>
                            <span>{{ $subtotal }} ريال</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>رسوم التوصيل:</span>
                            <span>{{ $deliveryFee }} ريال</span>
                        </div>
                        @if($discount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>الخصم:</span>
                                <span class="text-success">- {{ $discount }} ريال</span>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2">
                            <span>الضريبة ({{ $taxPercentage }}%):</span>
                            <span>{{ $taxAmount }} ريال</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">الإجمالي:</span>
                            <span class="fw-bold">{{ $total }} ريال</span>
                        </div>

                        <!-- كود الخصم -->
                        <form action="{{ route('frontend.cart.apply-coupon') }}" method="POST" class="mb-3">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control" name="coupon_code" placeholder="كود الخصم" value="{{ session('coupon_code') }}">
                                <button class="btn btn-outline-primary" type="submit">تطبيق</button>
                            </div>
                            @if(session('coupon_success'))
                                <div class="text-success small mt-2">
                                    <i class="fas fa-check-circle me-1"></i> {{ session('coupon_success') }}
                                </div>
                            @endif
                            @if(session('coupon_error'))
                                <div class="text-danger small mt-2">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ session('coupon_error') }}
                                </div>
                            @endif
                        </form>

                        <div class="d-grid gap-2">
                            <a href="{{ route('frontend.checkout') }}" class="btn btn-primary">
                                <i class="fas fa-credit-card me-2"></i> متابعة الدفع
                            </a>
                        </div>
                    </div>
                </div>

                <!-- الأطباق المقترحة -->
                @if(count($suggestedDishes) > 0)
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">قد يعجبك أيضاً</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($suggestedDishes as $dish)
                                    <div class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $dish->image_url }}" alt="{{ $dish->name }}" class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $dish->name }}</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">{{ $dish->chef->user->name }}</small>
                                                    <span class="fw-bold text-primary">{{ $dish->price }} ريال</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <form action="{{ route('frontend.cart.add', $dish->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-plus me-1"></i> أضف للسلة
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                        <h4>سلة التسوق فارغة</h4>
                        <p class="text-muted">لم تقم بإضافة أي أطباق إلى سلة التسوق بعد.</p>
                        <a href="{{ route('frontend.dishes') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-utensils me-2"></i> تصفح الأطباق
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تأكيد حذف عنصر من السلة
        const removeButtons = document.querySelectorAll('.remove-item');
        removeButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('هل أنت متأكد من حذف هذا الطبق من السلة؟')) {
                    this.closest('form').submit();
                }
            });
        });

        // تأكيد إفراغ السلة
        const clearCartButton = document.querySelector('.clear-cart');
        if (clearCartButton) {
            clearCartButton.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('هل أنت متأكد من إفراغ سلة التسوق بالكامل؟')) {
                    this.closest('form').submit();
                }
            });
        }
    });
</script>
@endsection
