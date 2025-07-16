@if(count($orders) > 0)
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col">رقم الطلب</th>
                    <th scope="col">العميل</th>
                    <th scope="col">التاريخ</th>
                    <th scope="col">الأطباق</th>
                    <th scope="col">المبلغ</th>
                    <th scope="col">حالة الطلب</th>
                    <th scope="col">حالة الدفع</th>
                    <th scope="col" width="180">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>
                            <strong>#{{ $order->order_number }}</strong>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $order->user->profile_image ?? '/images/default-user.jpg' }}" class="rounded-circle me-2" width="30" height="30" alt="{{ $order->user->name }}">
                                <div>
                                    <div>{{ $order->user->name }}</div>
                                    <small class="text-muted">{{ $order->user->phone }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $order->created_at->format('Y/m/d') }}</div>
                            <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $order->items_count }} {{ $order->items_count > 1 ? 'أطباق' : 'طبق' }}</span>
                        </td>
                        <td>
                            <strong>{{ number_format($order->total, 2) }} ريال</strong>
                        </td>
                        <td>
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
                        <td>
                            @if($order->is_paid)
                                <span class="badge bg-success">مدفوع</span>
                            @else
                                <span class="badge bg-danger">غير مدفوع</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary view-details-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#orderDetailsModal" 
                                        data-order-id="{{ $order->id }}" 
                                        data-order-number="{{ $order->order_number }}"
                                        title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                @if($order->status != 'completed' && $order->status != 'cancelled')
                                    <button type="button" class="btn btn-sm btn-outline-secondary change-status-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#changeStatusModal" 
                                            data-order-id="{{ $order->id }}" 
                                            data-status="{{ $order->status }}"
                                            title="تغيير الحالة">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                @endif
                                
                                <a href="{{ route('frontend.messages.conversation', $order->user_id) }}" class="btn btn-sm btn-outline-success" title="مراسلة العميل">
                                    <i class="fas fa-comment-alt"></i>
                                </a>
                                
                                <a href="{{ route('frontend.chef.orders.invoice', $order->id) }}" class="btn btn-sm btn-outline-info" title="الفاتورة" target="_blank">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- الترقيم -->
    <div class="d-flex justify-content-center p-3">
        {{ $orders->appends(request()->query())->links() }}
    </div>
@else
    <div class="alert alert-info m-3">
        <i class="fas fa-info-circle me-2"></i> لا توجد طلبات متاحة.
    </div>
@endif
