@extends('frontend.layouts.app')

@section('title', 'إتمام الطلب')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('frontend.cart') }}">سلة التسوق</a></li>
                    <li class="breadcrumb-item active" aria-current="page">إتمام الطلب</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">إتمام الطلب</h2>
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
        <form action="{{ route('frontend.checkout.process') }}" method="POST" id="checkout-form">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <!-- اختيار العنوان -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">عنوان التوصيل</h5>
                        </div>
                        <div class="card-body">
                            @if(count($addresses) > 0)
                                <div class="mb-3">
                                    <div class="row">
                                        @foreach($addresses as $address)
                                            <div class="col-md-6 mb-3">
                                                <div class="card h-100 {{ $address->is_default ? 'border-primary' : '' }}">
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="address_id" id="address_{{ $address->id }}" value="{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }} required>
                                                            <label class="form-check-label" for="address_{{ $address->id }}">
                                                                <strong>{{ $address->label }}</strong>
                                                                @if($address->is_default)
                                                                    <span class="badge bg-primary ms-2">العنوان الافتراضي</span>
                                                                @endif
                                                            </label>
                                                        </div>
                                                        <hr>
                                                        <p class="mb-1"><strong>المستلم:</strong> {{ $address->recipient_name }}</p>
                                                        <p class="mb-1"><strong>الهاتف:</strong> {{ $address->phone }}</p>
                                                        <address class="mb-0">
                                                            {{ $address->city }}، {{ $address->area }}،<br>
                                                            شارع {{ $address->street }}، مبنى {{ $address->building_no }}
                                                            @if($address->apartment_no)
                                                                ، شقة {{ $address->apartment_no }}
                                                            @endif
                                                            @if($address->landmark)
                                                                <br>
                                                                <small class="text-muted">{{ $address->landmark }}</small>
                                                            @endif
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                                    <a href="{{ route('frontend.customer.addresses.create') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-plus me-2"></i> إضافة عنوان جديد
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i> لا توجد عناوين مسجلة. يرجى إضافة عنوان للتوصيل.
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('frontend.customer.addresses.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i> إضافة عنوان جديد
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- ملاحظات التوصيل -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">ملاحظات التوصيل</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="delivery_notes" class="form-label">ملاحظات إضافية للتوصيل</label>
                                <textarea class="form-control" id="delivery_notes" name="delivery_notes" rows="2" placeholder="مثال: المنزل بجوار المسجد، اتصل قبل الوصول...">{{ old('delivery_notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- طريقة الدفع -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">طريقة الدفع</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash" checked required>
                                    <label class="form-check-label" for="payment_cash">
                                        <i class="fas fa-money-bill-wave me-2"></i> الدفع عند الاستلام
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card" required>
                                    <label class="form-check-label" for="payment_card">
                                        <i class="fas fa-credit-card me-2"></i> بطاقة ائتمانية
                                    </label>
                                </div>
                                
                                <!-- تفاصيل بطاقة الائتمان (تظهر عند اختيار الدفع بالبطاقة) -->
                                <div id="credit-card-details" class="mt-3 p-3 border rounded" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="card_number" class="form-label">رقم البطاقة</label>
                                            <input type="text" class="form-control" id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="expiry_date" class="form-label">تاريخ الانتهاء</label>
                                            <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/YY">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cvv" class="form-label">رمز الأمان (CVV)</label>
                                            <input type="text" class="form-control" id="cvv" name="cvv" placeholder="XXX">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="card_holder" class="form-label">اسم حامل البطاقة</label>
                                            <input type="text" class="form-control" id="card_holder" name="card_holder" placeholder="الاسم كما يظهر على البطاقة">
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                            <div class="mb-3">
                                <h6>الأطباق ({{ count($cartItems) }})</h6>
                                <div class="list-group list-group-flush">
                                    @foreach($cartItems as $item)
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item['dish']->image_url }}" alt="{{ $item['dish']->name }}" class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-0">{{ $item['dish']->name }}</h6>
                                                        <small class="text-muted">{{ $item['quantity'] }} × {{ $item['price'] }} ريال</small>
                                                    </div>
                                                </div>
                                                <span>{{ $item['price'] * $item['quantity'] }} ريال</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <hr>

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

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="terms_accepted" name="terms_accepted" required>
                                <label class="form-check-label" for="terms_accepted">
                                    أوافق على <a href="{{ route('frontend.terms') }}" target="_blank">الشروط والأحكام</a> وسياسة الخصوصية
                                </label>
                                @error('terms_accepted')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="place-order-btn">
                                    <i class="fas fa-check-circle me-2"></i> تأكيد الطلب
                                </button>
                                <a href="{{ route('frontend.cart') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-right me-2"></i> العودة للسلة
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- الوقت المتوقع للتوصيل -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">الوقت المتوقع للتوصيل</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-primary me-3 fa-2x"></i>
                                <div>
                                    <h6 class="mb-1">{{ $estimatedDeliveryTime }} دقيقة</h6>
                                    <p class="text-muted mb-0">الوقت التقريبي للتوصيل من وقت تأكيد الطلب</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                        <h4>سلة التسوق فارغة</h4>
                        <p class="text-muted">لا يمكن إتمام الطلب بدون إضافة أطباق إلى السلة.</p>
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
        // إظهار/إخفاء تفاصيل بطاقة الائتمان حسب طريقة الدفع المختارة
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const creditCardDetails = document.getElementById('credit-card-details');
        
        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                if (this.value === 'card') {
                    creditCardDetails.style.display = 'block';
                } else {
                    creditCardDetails.style.display = 'none';
                }
            });
        });
        
        // التحقق من وجود عنوان محدد قبل تقديم النموذج
        const checkoutForm = document.getElementById('checkout-form');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                const addressSelected = document.querySelector('input[name="address_id"]:checked');
                if (!addressSelected) {
                    e.preventDefault();
                    alert('يرجى اختيار عنوان للتوصيل');
                }
            });
        }
        
        // تنسيق حقول بطاقة الائتمان
        const cardNumberInput = document.getElementById('card_number');
        if (cardNumberInput) {
            cardNumberInput.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 16) {
                    value = value.slice(0, 16);
                }
                
                // إضافة مسافات بعد كل 4 أرقام
                let formattedValue = '';
                for (let i = 0; i < value.length; i++) {
                    if (i > 0 && i % 4 === 0) {
                        formattedValue += ' ';
                    }
                    formattedValue += value[i];
                }
                
                this.value = formattedValue;
            });
        }
        
        const expiryDateInput = document.getElementById('expiry_date');
        if (expiryDateInput) {
            expiryDateInput.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 4) {
                    value = value.slice(0, 4);
                }
                
                if (value.length > 2) {
                    this.value = value.slice(0, 2) + '/' + value.slice(2);
                } else {
                    this.value = value;
                }
            });
        }
        
        const cvvInput = document.getElementById('cvv');
        if (cvvInput) {
            cvvInput.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 3) {
                    value = value.slice(0, 3);
                }
                this.value = value;
            });
        }
    });
</script>
@endsection
