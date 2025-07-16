<div class="order-details">
    <!-- معلومات الطلب الأساسية -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">معلومات الطلب</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-0">رقم الطلب:</th>
                                <td class="text-end">#{{ $order->order_number }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">تاريخ الطلب:</th>
                                <td class="text-end">{{ $order->created_at->format('Y/m/d - h:i A') }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">حالة الطلب:</th>
                                <td class="text-end">
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="badge bg-primary">جديد</span>
                                            @break
                                        @case('processing')
                                            <span class="badge bg-warning text-dark">قيد التحضير</span>
                                            @break
                                        @case('ready')
                                            <span class="badge bg-info">جاهز للتسليم</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-success">مكتمل</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">ملغي</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $order->status }}</span>
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0">حالة الدفع:</th>
                                <td class="text-end">
                                    @if($order->is_paid)
                                        <span class="badge bg-success">مدفوع</span>
                                    @else
                                        <span class="badge bg-danger">غير مدفوع</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0">طريقة الدفع:</th>
                                <td class="text-end">{{ $order->payment_method }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">طريقة التوصيل:</th>
                                <td class="text-end">{{ $order->delivery_type }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">وقت التسليم المتوقع:</th>
                                <td class="text-end">{{ $order->expected_delivery_time }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">معلومات العميل</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $order->user->profile_image ?? '/images/default-user.jpg' }}" class="rounded-circle me-3" width="60" height="60" alt="{{ $order->user->name }}">
                        <div>
                            <h5 class="mb-1">{{ $order->user->name }}</h5>
                            <p class="text-muted mb-0">{{ $order->user->email }}</p>
                        </div>
                    </div>
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-0">رقم الهاتف:</th>
                                <td class="text-end">{{ $order->user->phone }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">عدد الطلبات السابقة:</th>
                                <td class="text-end">{{ $previousOrders }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">عضو منذ:</th>
                                <td class="text-end">{{ $order->user->created_at->format('Y/m/d') }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center pt-3">
                                    <a href="{{ route('frontend.messages.conversation', $order->user_id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-comment-alt me-2"></i> مراسلة العميل
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- عنوان التوصيل -->
    @if($order->address)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">عنوان التوصيل</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <p><strong>{{ $order->address->address_type }}:</strong> {{ $order->address->address_line }}</p>
                    <p><strong>المدينة:</strong> {{ $order->address->city }}</p>
                    <p><strong>ملاحظات إضافية:</strong> {{ $order->address->notes ?? 'لا توجد ملاحظات' }}</p>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <a href="https://maps.google.com/?q={{ $order->address->latitude }},{{ $order->address->longitude }}" target="_blank" class="btn btn-outline-primary">
                            <i class="fas fa-map-marker-alt me-2"></i> عرض على الخريطة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- تفاصيل الأطباق المطلوبة -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">الأطباق المطلوبة</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="80">الصورة</th>
                            <th scope="col">الطبق</th>
                            <th scope="col">السعر</th>
                            <th scope="col">الكمية</th>
                            <th scope="col">الإجمالي</th>
                            <th scope="col">ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <img src="{{ $item->dish->image_url }}" alt="{{ $item->dish->name }}" class="img-thumbnail" width="60" height="60" style="object-fit: cover;">
                                </td>
                                <td>
                                    <strong>{{ $item->dish->name }}</strong>
                                    @if($item->options)
                                        <div class="small text-muted">
                                            @foreach($item->options as $option)
                                                <span class="badge bg-light text-dark me-1">{{ $option }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td>{{ number_format($item->price, 2) }} ريال</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price * $item->quantity, 2) }} ريال</td>
                                <td>{{ $item->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- ملخص الطلب -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">ملاحظات الطلب</h5>
                </div>
                <div class="card-body">
                    @if($order->notes)
                        <p>{{ $order->notes }}</p>
                    @else
                        <p class="text-muted">لا توجد ملاحظات للطلب</p>
                    @endif
                    
                    @if($order->status_history && count($order->status_history) > 0)
                        <h6 class="mt-4 mb-3">سجل حالة الطلب</h6>
                        <div class="timeline">
                            @foreach($order->status_history as $history)
                                <div class="timeline-item">
                                    <div class="timeline-marker 
                                        @switch($history->status)
                                            @case('pending') bg-primary @break
                                            @case('processing') bg-warning @break
                                            @case('ready') bg-info @break
                                            @case('completed') bg-success @break
                                            @case('cancelled') bg-danger @break
                                            @default bg-secondary
                                        @endswitch
                                    "></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <p class="mb-0">
                                                @switch($history->status)
                                                    @case('pending') <strong>جديد</strong> @break
                                                    @case('processing') <strong>قيد التحضير</strong> @break
                                                    @case('ready') <strong>جاهز للتسليم</strong> @break
                                                    @case('completed') <strong>مكتمل</strong> @break
                                                    @case('cancelled') <strong>ملغي</strong> @break
                                                    @default <strong>{{ $history->status }}</strong>
                                                @endswitch
                                            </p>
                                            <small class="text-muted">{{ $history->created_at->format('Y/m/d - h:i A') }}</small>
                                        </div>
                                        @if($history->note)
                                            <p class="text-muted small mb-0">{{ $history->note }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">ملخص المبلغ</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>إجمالي الأطباق:</th>
                                <td class="text-end">{{ number_format($order->subtotal, 2) }} ريال</td>
                            </tr>
                            @if($order->discount > 0)
                                <tr>
                                    <th>الخصم:</th>
                                    <td class="text-end">- {{ number_format($order->discount, 2) }} ريال</td>
                                </tr>
                            @endif
                            <tr>
                                <th>رسوم التوصيل:</th>
                                <td class="text-end">{{ number_format($order->delivery_fee, 2) }} ريال</td>
                            </tr>
                            <tr>
                                <th>الضريبة ({{ $order->tax_percentage }}%):</th>
                                <td class="text-end">{{ number_format($order->tax, 2) }} ريال</td>
                            </tr>
                            <tr class="border-top">
                                <th class="h5">الإجمالي:</th>
                                <td class="text-end h5">{{ number_format($order->total, 2) }} ريال</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('frontend.chef.orders.invoice', $order->id) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-file-invoice me-2"></i> عرض الفاتورة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- أزرار الإجراءات -->
    @if($order->status != 'completed' && $order->status != 'cancelled')
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">تحديث حالة الطلب</h5>
                    </div>
                    <div class="btn-group" role="group">
                        @if($order->status == 'pending')
                            <form action="{{ route('frontend.chef.orders.update-status', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="processing">
                                <button type="submit" class="btn btn-warning me-2">
                                    <i class="fas fa-utensils me-2"></i> بدء التحضير
                                </button>
                            </form>
                        @endif
                        
                        @if($order->status == 'processing')
                            <form action="{{ route('frontend.chef.orders.update-status', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="ready">
                                <button type="submit" class="btn btn-info me-2">
                                    <i class="fas fa-check-circle me-2"></i> تم التحضير
                                </button>
                            </form>
                        @endif
                        
                        @if($order->status == 'ready')
                            <form action="{{ route('frontend.chef.orders.update-status', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="fas fa-check-double me-2"></i> تم التسليم
                                </button>
                            </form>
                        @endif
                        
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                            <i class="fas fa-times-circle me-2"></i> إلغاء الطلب
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- مودال إلغاء الطلب -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">إلغاء الطلب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> هل أنت متأكد من إلغاء هذا الطلب؟ لا يمكن التراجع عن هذا الإجراء.
                </div>
                <form id="cancel-order-form" action="{{ route('frontend.chef.orders.update-status', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <div class="mb-3">
                        <label for="cancel_reason" class="form-label">سبب الإلغاء</label>
                        <select class="form-select" id="cancel_reason" name="cancel_reason" required>
                            <option value="">اختر سبب الإلغاء</option>
                            <option value="out_of_stock">نفاذ المكونات</option>
                            <option value="customer_request">بناءً على طلب العميل</option>
                            <option value="technical_issue">مشكلة فنية</option>
                            <option value="other">سبب آخر</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cancel_note" class="form-label">ملاحظات إضافية</label>
                        <textarea class="form-control" id="cancel_note" name="note" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" id="confirm-cancel-order">تأكيد الإلغاء</button>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        left: -30px;
        top: 5px;
    }
    
    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: -23px;
        top: 20px;
        height: calc(100% - 20px);
        width: 2px;
        background-color: #e9ecef;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تأكيد إلغاء الطلب
        document.getElementById('confirm-cancel-order').addEventListener('click', function() {
            const form = document.getElementById('cancel-order-form');
            const reason = document.getElementById('cancel_reason').value;
            
            if (!reason) {
                alert('يرجى اختيار سبب الإلغاء');
                return;
            }
            
            form.submit();
        });
    });
</script>
