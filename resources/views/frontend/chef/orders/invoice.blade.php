<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة الطلب #{{ $order->order_number }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        
        .invoice-header {
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .logo {
            max-height: 70px;
        }
        
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .invoice-details {
            margin-bottom: 30px;
        }
        
        .invoice-details-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .invoice-table {
            margin-bottom: 30px;
        }
        
        .invoice-table th {
            background-color: #f8f9fa;
        }
        
        .invoice-summary {
            margin-top: 30px;
            border-top: 2px solid #f0f0f0;
            padding-top: 20px;
        }
        
        .invoice-summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .invoice-total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #f0f0f0;
        }
        
        .invoice-footer {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            color: #777;
            border-top: 1px solid #f0f0f0;
            padding-top: 20px;
        }
        
        .payment-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .payment-status.paid {
            background-color: #d4edda;
            color: #155724;
        }
        
        .payment-status.unpaid {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .order-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .order-status.pending {
            background-color: #cfe2ff;
            color: #084298;
        }
        
        .order-status.processing {
            background-color: #fff3cd;
            color: #664d03;
        }
        
        .order-status.ready {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        
        .order-status.completed {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        
        .order-status.cancelled {
            background-color: #f8d7da;
            color: #842029;
        }
        
        .qr-code {
            text-align: center;
            margin-top: 20px;
        }
        
        .qr-code img {
            max-width: 100px;
        }
        
        @media print {
            body {
                background-color: #fff;
            }
            
            .invoice-container {
                box-shadow: none;
                margin: 0;
                padding: 15px;
                max-width: 100%;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="invoice-container">
            <div class="invoice-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <img src="{{ asset('images/logo.png') }}" alt="شعار المطعم" class="logo">
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="invoice-title">فاتورة طلب</div>
                        <div>رقم الطلب: #{{ $order->order_number }}</div>
                        <div>تاريخ الطلب: {{ $order->created_at->format('Y/m/d') }}</div>
                        <div>وقت الطلب: {{ $order->created_at->format('h:i A') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="row invoice-details">
                <div class="col-md-6">
                    <h5 class="mb-3">معلومات العميل</h5>
                    <div><strong>الاسم:</strong> {{ $order->user->name }}</div>
                    <div><strong>البريد الإلكتروني:</strong> {{ $order->user->email }}</div>
                    <div><strong>رقم الهاتف:</strong> {{ $order->user->phone }}</div>
                    @if($order->address)
                        <div><strong>العنوان:</strong> {{ $order->address->address_line }}, {{ $order->address->city }}</div>
                    @endif
                </div>
                <div class="col-md-6 text-md-end">
                    <h5 class="mb-3">معلومات الطلب</h5>
                    <div>
                        <strong>حالة الطلب:</strong> 
                        <span class="order-status {{ $order->status }}">
                            @switch($order->status)
                                @case('pending')
                                    جديد
                                    @break
                                @case('processing')
                                    قيد التحضير
                                    @break
                                @case('ready')
                                    جاهز للتسليم
                                    @break
                                @case('completed')
                                    مكتمل
                                    @break
                                @case('cancelled')
                                    ملغي
                                    @break
                                @default
                                    {{ $order->status }}
                            @endswitch
                        </span>
                    </div>
                    <div>
                        <strong>حالة الدفع:</strong> 
                        <span class="payment-status {{ $order->is_paid ? 'paid' : 'unpaid' }}">
                            {{ $order->is_paid ? 'مدفوع' : 'غير مدفوع' }}
                        </span>
                    </div>
                    <div><strong>طريقة الدفع:</strong> {{ $order->payment_method }}</div>
                    <div><strong>طريقة التوصيل:</strong> {{ $order->delivery_type }}</div>
                </div>
            </div>
            
            <div class="invoice-table">
                <h5 class="mb-3">تفاصيل الطلب</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الطبق</th>
                                <th>السعر</th>
                                <th>الكمية</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div>{{ $item->dish->name }}</div>
                                        @if($item->options)
                                            <small class="text-muted">
                                                {{ implode(', ', $item->options) }}
                                            </small>
                                        @endif
                                        @if($item->notes)
                                            <small class="text-muted d-block">
                                                <em>{{ $item->notes }}</em>
                                            </small>
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->price, 2) }} ريال</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price * $item->quantity, 2) }} ريال</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    @if($order->notes)
                        <div class="mb-4">
                            <h5 class="mb-2">ملاحظات الطلب</h5>
                            <p>{{ $order->notes }}</p>
                        </div>
                    @endif
                    
                    <div class="qr-code">
                        <img src="data:image/png;base64,{{ $qrCode }}" alt="رمز QR للطلب">
                        <div class="mt-2 small text-muted">امسح الرمز لتتبع الطلب</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="invoice-summary">
                        <div class="invoice-summary-row">
                            <div>إجمالي الأطباق:</div>
                            <div>{{ number_format($order->subtotal, 2) }} ريال</div>
                        </div>
                        @if($order->discount > 0)
                            <div class="invoice-summary-row">
                                <div>الخصم:</div>
                                <div>- {{ number_format($order->discount, 2) }} ريال</div>
                            </div>
                        @endif
                        <div class="invoice-summary-row">
                            <div>رسوم التوصيل:</div>
                            <div>{{ number_format($order->delivery_fee, 2) }} ريال</div>
                        </div>
                        <div class="invoice-summary-row">
                            <div>الضريبة ({{ $order->tax_percentage }}%):</div>
                            <div>{{ number_format($order->tax, 2) }} ريال</div>
                        </div>
                        <div class="invoice-summary-row invoice-total">
                            <div>الإجمالي:</div>
                            <div>{{ number_format($order->total, 2) }} ريال</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="invoice-footer">
                <p>شكراً لطلبك من منصتنا!</p>
                <p>للاستفسارات، يرجى التواصل معنا على {{ config('app.phone') }} أو {{ config('app.email') }}</p>
            </div>
            
            <div class="text-center mt-4 no-print">
                <button class="btn btn-primary me-2" onclick="window.print()">
                    <i class="fas fa-print me-2"></i> طباعة الفاتورة
                </button>
                <a href="{{ route('frontend.chef.orders') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i> العودة للطلبات
                </a>
            </div>
        </div>
    </div>
</body>
</html>
