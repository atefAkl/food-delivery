@extends('frontend.layouts.app')

@section('title', 'المحادثة مع ' . $otherUser->name)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('frontend.messages') }}">الرسائل</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $otherUser->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- معلومات المستخدم الآخر -->
                        <div class="col-md-4 border-end">
                            <div class="p-4 text-center">
                                <img src="{{ $otherUser->profile_image ?? '/images/default-user.jpg' }}" class="rounded-circle mb-3" alt="{{ $otherUser->name }}" width="100" height="100" style="object-fit: cover;">
                                <h5>{{ $otherUser->name }}</h5>
                                <p class="text-muted mb-3">
                                    @if($otherUser->type === 'chef')
                                        شيف
                                        @if($otherUser->chef->speciality)
                                            - {{ $otherUser->chef->speciality }}
                                        @endif
                                    @elseif($otherUser->type === 'customer')
                                        عميل
                                    @endif
                                </p>
                                
                                @if($otherUser->type === 'chef')
                                    <div class="mb-3">
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $otherUser->chef->average_rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @elseif($i - 0.5 <= $otherUser->chef->average_rating)
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-1">({{ $otherUser->chef->ratings_count }})</span>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('frontend.chef.show', $otherUser->chef->id) }}" class="btn btn-outline-primary mb-3">
                                        <i class="fas fa-user me-2"></i> عرض الملف الشخصي
                                    </a>
                                    
                                    <a href="{{ route('frontend.chef.dishes', $otherUser->chef->id) }}" class="btn btn-primary">
                                        <i class="fas fa-utensils me-2"></i> عرض الأطباق
                                    </a>
                                @endif
                            </div>
                            
                            <hr>
                            
                            <!-- الطلبات المشتركة -->
                            @if(count($sharedOrders) > 0)
                                <div class="p-3">
                                    <h6 class="mb-3">الطلبات المشتركة</h6>
                                    <div class="list-group list-group-flush">
                                        @foreach($sharedOrders as $order)
                                            <a href="{{ route('frontend.' . (Auth::user()->type === 'customer' ? 'customer' : 'chef') . '.orders.show', $order->id) }}" class="list-group-item list-group-item-action">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-0">طلب #{{ $order->id }}</h6>
                                                        <small class="text-muted">{{ $order->created_at->format('Y/m/d') }}</small>
                                                    </div>
                                                    <span class="badge 
                                                        @if($order->status === 'pending') bg-warning 
                                                        @elseif($order->status === 'processing') bg-info 
                                                        @elseif($order->status === 'completed') bg-success 
                                                        @elseif($order->status === 'cancelled') bg-danger 
                                                        @else bg-secondary @endif">
                                                        {{ __('orders.status.' . $order->status) }}
                                                    </span>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <div class="p-3">
                                <a href="{{ route('frontend.messages') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-arrow-right me-2"></i> العودة إلى قائمة المحادثات
                                </a>
                            </div>
                        </div>
                        
                        <!-- محتوى المحادثة -->
                        <div class="col-md-8">
                            <div class="d-flex flex-column h-100">
                                <!-- رأس المحادثة -->
                                <div class="p-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">المحادثة مع {{ $otherUser->name }}</h5>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="conversationMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="conversationMenu">
                                                <li>
                                                    <button class="dropdown-item" type="button" onclick="document.getElementById('clearConversationForm').submit();">
                                                        <i class="fas fa-trash-alt me-2 text-danger"></i> حذف المحادثة
                                                    </button>
                                                    <form id="clearConversationForm" action="{{ route('frontend.messages.clear', $conversation->id) }}" method="POST" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- الرسائل -->
                                <div class="p-3 flex-grow-1 messages-container" id="messagesContainer" style="height: 500px; overflow-y: auto;">
                                    @if(count($messages) > 0)
                                        @php
                                            $currentDate = null;
                                        @endphp
                                        
                                        @foreach($messages as $message)
                                            @php
                                                $messageDate = $message->created_at->format('Y-m-d');
                                                $showDate = $currentDate !== $messageDate;
                                                $currentDate = $messageDate;
                                            @endphp
                                            
                                            @if($showDate)
                                                <div class="text-center my-3">
                                                    <span class="badge bg-light text-dark px-3 py-2">
                                                        @if($message->created_at->isToday())
                                                            اليوم
                                                        @elseif($message->created_at->isYesterday())
                                                            الأمس
                                                        @else
                                                            {{ $message->created_at->format('Y/m/d') }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                            
                                            <div class="message mb-3 {{ $message->sender_id === Auth::id() ? 'text-end' : '' }}">
                                                <div class="d-inline-block p-3 rounded {{ $message->sender_id === Auth::id() ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 75%;">
                                                    <p class="mb-0">{{ $message->message }}</p>
                                                </div>
                                                <div class="mt-1">
                                                    <small class="text-muted">{{ $message->created_at->format('h:i A') }}</small>
                                                    @if($message->sender_id === Auth::id())
                                                        @if($message->is_read)
                                                            <i class="fas fa-check-double text-primary ms-1" title="تم القراءة"></i>
                                                        @else
                                                            <i class="fas fa-check text-muted ms-1" title="تم الإرسال"></i>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center my-5">
                                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                            <p>لا توجد رسائل بعد. ابدأ المحادثة الآن!</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- نموذج إرسال رسالة -->
                                <div class="p-3 border-top">
                                    <form action="{{ route('frontend.messages.send') }}" method="POST" id="messageForm">
                                        @csrf
                                        <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                                        <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="message" placeholder="اكتب رسالتك هنا..." required autofocus>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-paper-plane"></i> إرسال
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تمرير إلى آخر الرسائل
        const messagesContainer = document.getElementById('messagesContainer');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        // تحديث حالة القراءة للرسائل
        fetch('{{ route("frontend.messages.mark-read", $conversation->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        // إرسال الرسالة بالضغط على Enter
        const messageInput = document.querySelector('input[name="message"]');
        if (messageInput) {
            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById('messageForm').submit();
                }
            });
        }
    });
</script>
@endsection

@section('styles')
<style>
    .messages-container {
        background-color: #f8f9fa;
    }
</style>
@endsection
